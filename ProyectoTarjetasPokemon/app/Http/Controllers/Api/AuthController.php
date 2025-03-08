<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Cart;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Registra un nuevo usuario y le asigna un carrito por defecto.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */


     public function register(Request $request)
     {
         try {
             Log::info('Datos recibidos:', $request->all()); // Para debug
     
             $validated = $request->validate([
                 'name' => 'required|string|max:255',
                 'email' => 'required|string|email|max:255|unique:users',
                 'password' => 'required|string|min:8',
                 'password_confirmation' => 'required|same:password',
                 'phone' => 'nullable|string|max:20',
                 'role' => 'nullable|string|in:admin,customer',
             ]);
     
             DB::beginTransaction();
             
             $user = User::create([
                 'name' => $validated['name'],
                 'email' => $validated['email'],
                 'password' => Hash::make($validated['password']),
                 'phone' => $validated['phone'] ?? null,
                 'role' => $validated['role'] ?? 'customer',
             ]);
     
             // Crear carrito para el usuario
             Cart::create(['user_id' => $user->id]);
             
             $token = $user->createToken('auth_token')->plainTextToken;
             
             DB::commit();
     
             return response()->json([
                 'message' => 'Usuario registrado exitosamente',
                 'user' => $user,
                 'token' => $token
             ], 201);
     
         } catch (ValidationException $e) {
             DB::rollBack();
             Log::error('Error de validación:', $e->errors());
             return response()->json([
                 'message' => 'Error de validación',
                 'errors' => $e->errors()
             ], 422);
         } catch (\Exception $e) {
             DB::rollBack();
             Log::error('Error en registro:', ['error' => $e->getMessage()]);
             return response()->json([
                 'message' => 'Error al registrar usuario',
                 'error' => $e->getMessage()
             ], 500);
         }
     }

    /**
     * Inicia sesión de usuario y genera un token de autenticación.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        $user = User::where('email', $request->email)->first();
    
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'error' => ['Credenciales inválidas.']
            ]);
        }
    
        try {
            // Verificar si el usuario tiene un carrito, si no, crearlo
            if (!$user->cart) {
                Cart::create(['user_id' => $user->id]);
            }
    
            Auth::login($user);
            
            $request->session()->regenerate();
            
            $token = $user->createToken('auth_token')->plainTextToken;
            
            $redirectUrl = $user->isAdmin() ? '/admin/products' : '/Inicio';
            
            return response()->json([
                'user' => $user,
                'token' => $token,
                'redirect' => $redirectUrl
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => 'Ocurrió un error en el inicio de sesión.'], 500);
        }
    }

    /**
     * Cierra la sesión del usuario actual.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        try {
            // Revocar el token actual
            $request->user()->currentAccessToken()->delete();
            
            return response()->json([
                'message' => 'Sesión cerrada correctamente'
            ], 200);
        } catch (Exception $e) {
            Log::error('Error en logout:', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Error al cerrar sesión',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtiene los datos del usuario autenticado.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function user(Request $request)
    {
        try {
            return response()->json($request->user());
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al obtener el usuario.'], 500);
        }
    }

    /**
     * Actualiza el perfil del usuario autenticado.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'address' => 'sometimes|string',
            'phone' => 'sometimes|string',
        ]);

        try {
            $user = $request->user();
            $user->update($request->only(['name', 'address', 'phone']));

            return response()->json(['user' => $user, 'message' => 'Perfil actualizado correctamente']);
        } catch (Exception $e) {
            return response()->json(['error' => 'No se pudo actualizar el perfil.'], 500);
        }
    }
}
