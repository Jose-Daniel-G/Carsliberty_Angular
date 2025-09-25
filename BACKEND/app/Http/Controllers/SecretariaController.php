<?php

namespace App\Http\Controllers;

use App\Models\Secretaria;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class SecretariaController extends Controller
{
    public function __construct()
    {  // Solo los que tengan el permiso pueden acceder a estas acciones
        $this->middleware('can:admin.secretarias.index')->only('index');
        $this->middleware('can:admin.secretarias.create')->only('create', 'store');
        $this->middleware('can:admin.secretarias.edit')->only('edit', 'update');
        $this->middleware('can:admin.secretarias.destroy')->only('destroy');
    }
    public function index()
    {
        $secretarias = Secretaria::with('user')->get(); // viene con la relacion del secretaria
        return view('admin.secretarias.index', compact('secretarias'));
    }
    // public function create(){return view('admin.secretarias.create');}
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombres' => 'required',
            'apellidos' => 'required',
            'cc' => 'required|unique:secretarias',
            'celular' => 'required',
            'direccion' => 'required',
            'correo' => 'required|email|max:250|unique:users,email',
            'fecha_nacimiento' => 'required|max:150',
            'password' => 'required|min:8|max:20|confirmed',
        ]);
        try {

            $usuario = User::create([
                'name' => $request->nombres,
                'email' => $request->correo,
                'password' => Hash::make($request->password ?? $request->cc),
            ]);

            $secretaria = new Secretaria();
            $secretaria->user_id = $usuario->id;
            $secretaria->nombres = $request->nombres;
            $secretaria->apellidos = $request->apellidos;
            $secretaria->cc = $request->cc;
            $secretaria->celular = $request->celular;
            $secretaria->direccion = $request->direccion;
            $secretaria->fecha_nacimiento = Carbon::createFromFormat('Y-m-d', $request->fecha_nacimiento)->format('d/m/Y');

            $secretaria->save();
            $usuario->assignRole('secretaria');

            return redirect()->route('admin.secretarias.index')
                ->with(['title' => 'Exito', 'info' => 'Se registro a la secretaria de forma correcta', 'icono' => 'success']);
        } catch (\Exception $exception) {
                return back()->withInput()->with(['title' => 'Error','info'  => 'No se registró la secretaria. ' . $exception->getMessage(),'icono' => 'error','openModal' => 'createModal' ]);// <- clave para reabrir el modal // return back()->withErrors(['error' => 'Ocurrió un error inesperado.'])->withInput();
        }
    }

    public function show(Secretaria $secretaria)
    {
        return view('admin.secretarias.show', compact('secretaria'));
    }

    public function edit(Secretaria $secretaria)
    { $secretaria->load('user');
      return response()->json($secretaria);}

    public function update(Request $request, Secretaria $secretaria)
    {
        $request->validate([
            'nombres' => 'required',
            'apellidos' => 'required',
            'cc' => 'required|unique:secretarias,cc,' . $secretaria->id,
            'celular' => 'required',
            'direccion' => 'required',
            'email' => 'required|email|max:250|unique:users,email,' . $secretaria->user->id,
            'fecha_nacimiento' => 'required|max:150',
            'password' => 'nullable|max:20|confirmed',
        ]);

        $secretaria->nombres = $request->nombres;
        $secretaria->apellidos = $request->apellidos;
        $secretaria->cc = $request->cc;
        $secretaria->celular = $request->celular;
        $secretaria->direccion = $request->direccion;
        // dd($secretaria->fecha_nacimiento);
        $secretaria->fecha_nacimiento = Carbon::createFromFormat('Y-m-d', $request->fecha_nacimiento)->format('d/m/Y');
        $secretaria->save();

        // $usuario = new User::find($secretaria->user->id);
        $usuario = $secretaria->user;  // Obtén el usuario existente
        $usuario->name = $request->nombres;
        $usuario->email = $request->email;
        if ($request->filled('password')) {$usuario->password = Hash::make($request->password);}
        $usuario->save();
        return redirect()->route('admin.secretarias.index')->with(['title' => 'Exito', 'info' => 'Se actualizo la secretaria de forma correcta', 'icono' => 'success']);
    }
    public function toggleStatus($id) //DEACTIVATE
    {
        $user = User::findOrFail($id);
        $user->status = !$user->status;
        $user->save();

        return redirect()->back()->with(['success' => 'Estado del usuario actualizado.']);
    }
    public function destroy(Secretaria $secretaria)
    {
        $user = $secretaria->user;
        $user->delete();
        $secretaria->delete();

        return redirect()->route('admin.secretarias.index')
            ->with(['title', 'Exito','info', 'La secretaria se eliminó con éxito','icono', 'success']);
    }
}
