<?php
namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;
use Exception;

class UserController extends Controller
{
    public function index()
    {
        try {
            $users = User::all();
            return response()->json($users, 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al obtener los usuarios', 'message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'username' => 'required|string|max:255',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6',
            ]);

            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return response()->json($user, 201);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Error al crear el usuario', 'message' => $e->getMessage()], 500);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error inesperado', 'message' => $e->getMessage()], 500);
        }
    }

    public function createAdmin(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ]);

            $admin = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'admin', // Asigna el rol de administrador
            ]);

            return response()->json(['message' => 'Admin creado con éxito', 'admin' => $admin], 201);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Error al crear el administrador', 'message' => $e->getMessage()], 500);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error inesperado', 'message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $user = User::findOrFail($id);
            return response()->json($user, 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Usuario no encontrado', 'message' => $e->getMessage()], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            $request->validate([
                'username' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $id,
            ]);

            $user->update($request->only(['username', 'email']));

            return response()->json($user, 200);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Error al actualizar el usuario', 'message' => $e->getMessage()], 500);
        } catch (Exception $e) {
            return response()->json(['error' => 'Usuario no encontrado', 'message' => $e->getMessage()], 404);
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return response()->json(['message' => 'Usuario eliminado'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al eliminar el usuario', 'message' => $e->getMessage()], 500);
        }
    }
}
