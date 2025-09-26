<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Curso;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class ClienteController extends Controller
{

    public function __construct()
    {  // Solo los que tengan el permiso pueden acceder a estas acciones
        // $this->middleware('can:admin.clientes.index')->only('index');
        // $this->middleware('can:admin.clientes.create')->only('create', 'store');
        // $this->middleware('can:admin.clientes.edit')->only('edit', 'update');
        // $this->middleware('can:admin.clientes.destroy')->only('destroy');
    }

    public function index(Request $request)
    {
        $clientes = Cliente::with('user')->paginate(10);
        $cursos = Curso::all();

        return response()->json(['cursos' => $cursos, 'clientes' => $clientes], 200);
    }
    // public function create(){// $cursos = Curso::all();// return view('admin.clientes.create', compact('cursos'));}
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombres' => 'required',
            'apellidos' => 'required',
            'cc' => 'required|max:11|unique:clientes,cc',
            'genero' => 'required',
            'celular' => 'required|max:11',
            'correo' => 'required|email|max:250|unique:users,email',
            'direccion' => 'required',
            'contacto_emergencia' => 'required|max:11',
        ]);
        try {
            DB::beginTransaction();  // ⬅️ Comienza la transacción

            $usuario = User::create(['name' => $request->nombres, 'email' => $request->correo, 'password' => Hash::make($request->password ?? $request->cc),]);
            $usuario->assignRole('cliente');

            $validatedData['user_id'] = $usuario->id;                            // Asociar el user_id al cliente
            unset($validatedData['correo']);
            $validatedData['observaciones'] = $request->observaciones;

            $cliente = Cliente::create($validatedData);                          // Crear cliente
            $usuarioId = $usuario->id;

            if ($request->has('cursos') && is_array($request->cursos)) {          // Asignar cursos si existen
                foreach ($request->cursos as $cursoId) {
                    $cliente->cursos()->attach($cursoId, ['horas_realizadas' => 0]);
                }
            }
            if (!isset($cliente)) { // $agenda->delete();      
                DB::rollBack();                                                   // Revertir todo si algo falla
                DB::table('users')->where('id', $usuarioId)->delete(); // Definir $ultimoId tomando el máximo ID de la tabla
            }
            DB::commit();  // ⬅️ Si todo salió bien, guarda en la base de datos
            return redirect()->route('admin.clientes.index')
                ->with(['title' => 'Éxito', 'info' => 'Se registró al Cliente de forma correcta', 'icono' => 'success']);
        } catch (\Illuminate\Database\QueryException $e) {
            \Log::error('Error de base de datos al registrar cliente: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Error en la base de datos.'])->withInput();
        } catch (\Exception $e) {
            DB::rollBack();  // ⬅️ Si falla, revierte todo
            \Log::error('Error inesperado al registrar cliente: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Ocurrió un error inesperado.'])->withInput();
        }
    }
    // public function show(Cliente $cliente) {  return view('admin.clientes.show', compact('cliente'));  }
    public function edit(Cliente $cliente)
    {
        $cursos = Curso::all();
        $cliente->load('user'); // Cargar todos los cursos disponibles y usuario del cliente
        $cursosSeleccionados = $cliente->cursos->pluck('id')->toArray(); // Obtener los cursos ya asignados al cliente
        // return view('admin.clientes.edit', compact('cliente', 'cursos', 'cursosSeleccionados'));
        return response()->json(['cliente' => $cliente, 'cursos' => $cursos, 'cursosSeleccionados' => $cursosSeleccionados]);
    }


    public function update(Request $request, Cliente $cliente)
    {   // \Log::info('cliente',[$request->all()]);
        $validatedData = $request->validate([
            'nombres' => 'required',
            'apellidos' => 'required',
            'cc' => 'required|unique:clientes,cc,' . $cliente->id,
            'genero' => 'required',
            'celular' => 'required',
            'email' => 'required|email|max:250|unique:users,email,' . $cliente->user_id, // validar en users
            'direccion' => 'required',
            'contacto_emergencia' => 'required',
        ]);

        // Actualizar datos del usuario (email)
        $usuario = User::findOrFail($cliente->user_id);
        $usuario->email = $request->email;

        if ($request->has('reset_password')) {
            $usuario->password = Hash::make($request->cc);
        }
        $usuario->save();

        unset($validatedData['email']);                   // Quitar el email del array porque no existe en clientes
        $cliente->observaciones = $request->observaciones;

        $cliente->update($validatedData);                 // Actualizar Cliente

        $cliente->cursos()->sync($request->cursos ?? []); // Sincronizar cursos

        return redirect()->route('admin.clientes.index')
            ->with(['title' => 'Exito', 'info' => 'Cliente actualizado correctamente.', 'icono' => 'success']);
    }

    public function toggleStatus($id) //DEACTIVATE
    {
        $user = User::findOrFail($id);
        $user->status = !$user->status;
        $user->save();

        return redirect()->back()->with(['success' => 'Estado del usuario actualizado.']);
    }

    // public function destroy(Cliente $Cliente)
    // {
    //     if ($Cliente->user) {$Cliente->user->delete();} // Si existe un usuario asociado, eliminarlo
    //     $Cliente->delete();// Eliminar el Cliente
    //     return redirect()->route('admin.clientes.index')->with(['title', 'Exito', 'info', 'El Cliente se eliminó con éxito', 'icono', 'success']);
    // }
}
