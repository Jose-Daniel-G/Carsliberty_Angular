<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organismo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash; // <-- Agregar esta línea
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    // public function __construct()
    // {   $this->middleware('can:admin.user.index')->only('index');
    //     $this->middleware('can:admin.user.edit')->only('edit', 'update');
    // }
    public function index()
    {
        $users = User::where('id', '!=', Auth::id())->get();
        return view('admin.users.index', compact('users'));
    }
    // public function create() {  return view('admin.users.create'); }
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|max:250', 'email' => 'required|email|max:250|unique:users', 'password' => 'required|min:8|max:250|confirmed',]);
        $usuario = new User();
        $usuario->name = $request->name;
        $usuario->email = $request->email;
        $usuario->password = Hash::make($request->password);
        $usuario->save();

        // $username = explode('@', $usuario->email)[0];// // Extraer la parte antes del @ del email

        // Storage::makeDirectory("users/{$username}");// // Crear la carpeta en storage/app/users/{username}

        return redirect()->route('admin.users.index')->with('info', 'Se registro al usuario de forma correcta')->with('icono', 'success');
    }
    // public function show($id) {}
    public function edit($id)
    {
        $user = User::with('roles')->findOrFail($id);
        $roles = Role::all();

        return response()->json(['user' => $user,'roles' => $roles]);
    }

    public function update(Request $request, User $user)
    {
        $user->roles()->sync($request->roles);
        return redirect()->route('admin.users.index', $user)->with('info', 'Se asigno los roles correctamente');
    }

    public function toggleStatus($id) //DEACTIVATE
    {
        $user = User::findOrFail($id); $user->status = !$user->status;  $user->save();
        return redirect()->back()->with('success', 'Estado del usuario actualizado.');
    }
}
