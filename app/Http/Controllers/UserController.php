<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\Role;
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
        $query = User::with('role');

        // Filtro por término de búsqueda (nombre o correo)
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function ($q) use ($buscar) {
                $q->where('name', 'ILIKE', "%{$buscar}%")
                  ->orWhere('email', 'ILIKE', "%{$buscar}%");
            });
        }

        // Filtro por rol (puede ser por ID o por nombre de rol)
        if ($request->filled('rol_id')) {
            $query->where('rol_id', $request->rol_id);
        }

        // Filtro por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $usuarios = $query->orderBy('name')->paginate(10)->withQueryString();
        $roles = Role::orderBy('nombre')->get();

        return view('usuarios.index', compact('usuarios', 'roles'));
    }

    /**
     * Muestra el formulario para registrar un nuevo usuario (RF-021).
     */
    public function create()
    {
        $roles = Role::orderBy('nombre')->get();
        return view('usuarios.create', compact('roles'));
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
            'rol_id' => ['required', 'exists:roles,id'],
            'estado' => ['required', Rule::in([User::ESTADO_ACTIVO, User::ESTADO_INACTIVO])],
        ], [
            'name.required' => 'El nombre completo es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Ingrese un correo electrónico válido.',
            'email.unique' => 'Este correo electrónico ya se encuentra registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'rol_id.required' => 'Debe seleccionar un rol válido.',
            'rol_id.exists' => 'El rol seleccionado no existe en la base de datos.',
            'estado.required' => 'Debe seleccionar un estado.',
        ]);

        $nuevoUsuario = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'rol_id' => $validated['rol_id'],
            'estado' => $validated['estado'],
        ]);

        // RF-023: Auditoría de creación de usuario
        Auditoria::registrar(
            'CREAR_USUARIO',
            'Usuarios',
            "Creación del usuario {$nuevoUsuario->name} con rol {$nuevoUsuario->rol_nombre} y estado {$nuevoUsuario->estado}.",
            [
                'id' => $nuevoUsuario->id,
                'email' => $nuevoUsuario->email,
                'rol' => $nuevoUsuario->rol_nombre,
                'estado' => $nuevoUsuario->estado,
            ]
        );

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario registrado exitosamente.');
    }

    /**
     * Muestra el formulario de edición de usuario y rol (RF-021).
     */
    public function edit(User $usuario)
    {
        $roles = Role::orderBy('nombre')->get();
        return view('usuarios.edit', compact('usuario', 'roles'));
    }

    /**
     * Actualiza la información y rol del usuario (RF-021).
     */
    public function update(Request $request, User $usuario)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($usuario->id)],
            'rol_id' => ['required', 'exists:roles,id'],
            'estado' => ['required', Rule::in([User::ESTADO_ACTIVO, User::ESTADO_INACTIVO])],
            'password' => ['nullable', 'string', 'min:6'],
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo es obligatorio.',
            'email.unique' => 'Este correo ya pertenece a otro usuario.',
            'rol_id.required' => 'El rol es obligatorio.',
            'rol_id.exists' => 'El rol seleccionado no es válido.',
            'estado.required' => 'El estado es obligatorio.',
            'password.min' => 'La nueva contraseña debe tener al menos 6 caracteres.',
        ]);

        // Evitar que el administrador autenticado se inactive a sí mismo o se quite el rol de administrador
        if (Auth::id() === $usuario->id) {
            $adminRole = Role::where('nombre', Role::ADMIN)->first();
            if ($validated['estado'] !== User::ESTADO_ACTIVO) {
                return back()->withErrors(['estado' => 'No puedes desactivar tu propia cuenta de administrador.'])->withInput();
            }
            if ($adminRole && (int)$validated['rol_id'] !== $adminRole->id) {
                return back()->withErrors(['rol_id' => 'No puedes remover tu propio rol de administrador.'])->withInput();
            }
        }

        $datosAnteriores = [
            'name' => $usuario->name,
            'email' => $usuario->email,
            'rol' => $usuario->rol_nombre,
            'estado' => $usuario->estado,
        ];

        $usuario->name = $validated['name'];
        $usuario->email = $validated['email'];
        $usuario->rol_id = $validated['rol_id'];
        $usuario->estado = $validated['estado'];

        if (!empty($validated['password'])) {
            $usuario->password = Hash::make($validated['password']);
        }

        $usuario->save();

        // RF-023: Auditoría de actualización de usuario
        Auditoria::registrar(
            'ACTUALIZAR_USUARIO',
            'Usuarios',
            "Modificación del usuario {$usuario->name} (ID: {$usuario->id}).",
            [
                'antes' => $datosAnteriores,
                'despues' => [
                    'name' => $usuario->name,
                    'email' => $usuario->email,
                    'rol' => $usuario->fresh()->rol_nombre,
                    'estado' => $usuario->estado,
                ]
            ]
        );

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

        $infoEliminado = [
            'id' => $usuario->id,
            'name' => $usuario->name,
            'email' => $usuario->email,
            'rol' => $usuario->rol_nombre,
        ];

        $usuario->delete();

        // RF-023: Auditoría de eliminación
        Auditoria::registrar(
            'ELIMINAR_USUARIO',
            'Usuarios',
            "Eliminación del usuario {$infoEliminado['name']} ({$infoEliminado['email']}).",
            $infoEliminado
        );

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario eliminado exitosamente.');
    }
}
