<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Muestra la lista de usuarios (RF-021).
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Filtro por término de búsqueda (nombre o correo)
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function ($q) use ($buscar) {
                $q->where('name', 'ILIKE', "%{$buscar}%")
                  ->orWhere('email', 'ILIKE', "%{$buscar}%");
            });
        }

        // Filtro por rol
        if ($request->filled('rol')) {
            $query->where('rol', $request->rol);
        }

        // Filtro por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $usuarios = $query->orderBy('name')->paginate(10)->withQueryString();

        return view('usuarios.index', compact('usuarios'));
    }

    /**
     * Muestra el formulario para registrar un nuevo usuario (RF-021).
     */
    public function create()
    {
        return view('usuarios.create');
    }

    /**
     * Guarda el nuevo usuario en el sistema (RF-021).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'rol' => ['required', Rule::in([User::ROL_ADMIN, User::ROL_INSTRUCTOR, User::ROL_APRENDIZ])],
            'estado' => ['required', Rule::in([User::ESTADO_ACTIVO, User::ESTADO_INACTIVO])],
        ], [
            'name.required' => 'El nombre completo es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Ingrese un correo electrónico válido.',
            'email.unique' => 'Este correo electrónico ya se encuentra registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'rol.required' => 'Debe seleccionar un rol válido.',
            'estado.required' => 'Debe seleccionar un estado.',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'rol' => $validated['rol'],
            'estado' => $validated['estado'],
        ]);

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario registrado exitosamente con rol ' . ucfirst($validated['rol']) . '.');
    }

    /**
     * Muestra el formulario de edición de usuario y rol (RF-021).
     */
    public function edit(User $usuario)
    {
        return view('usuarios.edit', compact('usuario'));
    }

    /**
     * Actualiza la información y rol del usuario (RF-021).
     */
    public function update(Request $request, User $usuario)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($usuario->id)],
            'rol' => ['required', Rule::in([User::ROL_ADMIN, User::ROL_INSTRUCTOR, User::ROL_APRENDIZ])],
            'estado' => ['required', Rule::in([User::ESTADO_ACTIVO, User::ESTADO_INACTIVO])],
            'password' => ['nullable', 'string', 'min:6'],
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo es obligatorio.',
            'email.unique' => 'Este correo ya pertenece a otro usuario.',
            'rol.required' => 'El rol es obligatorio.',
            'estado.required' => 'El estado es obligatorio.',
            'password.min' => 'La nueva contraseña debe tener al menos 6 caracteres.',
        ]);

        // Evitar que el administrador autenticado se inactive a sí mismo o se quite el rol de administrador
        if (Auth::id() === $usuario->id) {
            if ($validated['estado'] !== User::ESTADO_ACTIVO) {
                return back()->withErrors(['estado' => 'No puedes desactivar tu propia cuenta de administrador.'])->withInput();
            }
            if ($validated['rol'] !== User::ROL_ADMIN) {
                return back()->withErrors(['rol' => 'No puedes remover tu propio rol de administrador.'])->withInput();
            }
        }

        $usuario->name = $validated['name'];
        $usuario->email = $validated['email'];
        $usuario->rol = $validated['rol'];
        $usuario->estado = $validated['estado'];

        if (!empty($validated['password'])) {
            $usuario->password = Hash::make($validated['password']);
        }

        $usuario->save();

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    /**
     * Elimina a un usuario del sistema (RF-021).
     */
    public function destroy(User $usuario)
    {
        // Evitar que el administrador se elimine a sí mismo
        if (Auth::id() === $usuario->id) {
            return redirect()->route('usuarios.index')
                ->with('error', 'No puedes eliminar tu propia cuenta de administrador.');
        }

        $usuario->delete();

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario eliminado exitosamente.');
    }
}
