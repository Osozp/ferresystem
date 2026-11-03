<?php

namespace App\Http\Controllers;

use App\Mail\OperatorInvitedMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $commerceId = Auth::user()->commerce_id;

        // Solo trae los usuarios que pertenecen al mismo comercio del usuario logueado
        $usuarios = User::where('commerce_id', $commerceId)->get();

        return view('admin.users.index', compact('usuarios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        // var_dump($request['email']);
        // exit;
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
        ]);

        $owner = Auth::user();
        $commerce = $owner->commerce;

        // Generar una contraseña temporal aleatoria de 8 caracteres
        $temporaryPassword = Str::random(8);

        // Crear el usuario ligado al comercio actual
        $user = User::create([
            'commerce_id' => $owner->commerce_id,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($temporaryPassword),
            'role' => 'collaborator',
            'provider' => 'direct',
            'email_verified_at' => now(),
        ]);

        // Enviar correo de invitación
        try {
            Mail::to($user->email)->send(new OperatorInvitedMail($user, $commerce, $temporaryPassword));
        } catch (\Exception $e) {
            // En caso de que falle el servicio de correo, la cuenta se crea de todos modos
            return redirect()->route('admin.users.index')->with('warning', 'Operario creado, pero no se pudo enviar el correo de invitación.');
        }

        return redirect()->route('admin.users.index')->with('success', 'Operario creado y correo de invitación enviado con éxito.');
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|string|email|max:255|unique:users',
    //         'password' => 'required|string|min:6',
    //     ]);

    //     $userCommerceId = Auth::user()->commerce_id;

    //     User::create([
    //         'commerce_id' => $userCommerceId, // Vinculado estrictamente al comercio del dueño actual
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'password' => Hash::make($request->password),
    //         'role' => 'operator', // Definimos el rol como operario/colaborador
    //         'provider' => 'direct',
    //         'email_verified_at' => now(),
    //     ]);

    //     return redirect()->route('admin.users.index')->with('success', 'Operario creado y vinculado al comercio con éxito.');
    // }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    public function toggleStatus(User $user)
    {
        // Verificar que el usuario pertenezca al mismo comercio del dueño logueado
        if ($user->commerce_id !== Auth::user()->commerce_id) {
            abort(403, 'Acción no autorizada.');
        }

        // No permitir que el dueño se bloquee a sí mismo
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'No puedes bloquear tu propia cuenta.');
        }

        // Alternar estado (asumiendo un campo booleano 'is_active' o un string 'status')
        // Ejemplo usando un campo booleano 'is_active':
        $user->is_active = !$user->is_active;
        $user->save();

        $statusMessage = $user->is_active ? 'activado' : 'bloqueado temporalmente';

        return redirect()->route('admin.users.index')->with('success', "El operario ha sido {$statusMessage}.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if ($user->commerce_id !== Auth::user()->commerce_id) {
            abort(403, 'Acción no autorizada.');
        }

        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Operario eliminado del sistema con éxito.');
    }
}
