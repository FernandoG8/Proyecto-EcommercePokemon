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
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'role' => 'nullable|string|in:admin,customer',
        ]);

        try {
            DB::beginTransaction();
            
            $role = $request->role ?? 'customer';
            
            // Crear usuario
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'address' => $request->address,
                'phone' => $request->phone,
                'role' => $role,
            ]);

            // Crear carrito para el usuario
            Cart::create(['user_id' => $user->id]);
            
            DB::commit();

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'user' => $user,
                'token' => $token
            ], 201);
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al registrar el usuario. Intente nuevamente.'], 500);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Ocurrió un error inesperado.'], 500);
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

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'user' => $user,
                'token' => $token
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
            $request->user()->currentAccessToken()->delete();
            return response()->json(['message' => 'Sesión cerrada correctamente']);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al cerrar sesión.'], 500);
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
