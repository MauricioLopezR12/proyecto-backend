<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    // Obtener todos los usuarios
    public function index()
    {
        $users = User::all();
        return response()->json($users, 200);
    }

    // Mostrar un usuario específico
    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }
        return response()->json($user, 200);
    }

    // Crear un nuevo usuario
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validar imagen
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images', 'public'); // Guardar imagen en storage/public/images
            $validated['image'] = $path; // Agregar la ruta de la imagen al array validado
        }

        $validated['password'] = Hash::make($validated['password']); // Hashear la contraseña

        $user = User::create($validated);

        return response()->json(['message' => 'Usuario creado correctamente', 'user' => $user], 201);
    }

    // Actualizar un usuario existente
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'sometimes|min:6', // La contraseña es opcional
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validar imagen
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images', 'public'); // Guardar nueva imagen
            $validated['image'] = $path;
        }

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']); // Hashear la contraseña si se proporciona
        }

        $user->update($validated);
        return response()->json(['message' => 'Usuario actualizado correctamente', 'user' => $user], 200);
    }

    // Eliminar un usuario
    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        $user->delete();
        return response()->json(['message' => 'Usuario eliminado correctamente'], 200);
    }
}
