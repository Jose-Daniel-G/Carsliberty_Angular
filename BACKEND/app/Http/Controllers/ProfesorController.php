<?php

namespace App\Http\Controllers;

use App\Models\Config;
use App\Models\Profesor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProfesorController extends Controller
{
    public function __construct()
    {  // Solo los que tengan el permiso pueden acceder a estas acciones
        // $this->middleware('can:admin.profesores.index')->only('index');
        // $this->middleware('can:admin.profesores.create')->only('create', 'store');
        // $this->middleware('can:admin.profesores.edit')->only('edit', 'update');
        // $this->middleware('can:admin.profesores.destroy')->only('destroy');
    }
    public function index()
    {
        $profesors = Profesor::with('user')->paginate(10);
        return response()->json(['profesors' => $profesors]);
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombres' => 'required',
            'apellidos' => 'required',
            'telefono' => 'required',
            'email' => 'required|email|max:150|unique:users,email', // Asegúrate de que el email sea único en la tabla users
            'password' => 'min:8|confirmed',
        ]);

        $usuario = new User();
        $usuario->name = $request->nombres;
        $usuario->email = $request->email;

        // Hash de la contraseña
        if ($request->filled('password')) {
            $usuario->password = Hash::make($request->password);
        }

        $usuario->save();
        $profesor = $request->all();
        $profesor['user_id'] = $usuario->id; // Asigna el ID del nuevo usuario al nuevo profesor

        Profesor::create($profesor);
        $usuario->assignRole('profesor');   // Asignar rol de 'profesor' al nuevo usuario

        return redirect()->route('admin.profesores.index')->with(['info', 'Se registró el profesor de forma correcta', 'icono', 'success']);
    }

    public function show(Profesor $profesor)
    {
        $profesor->load('user');
        return response()->json($profesor); // return view('admin.profesores.show', compact('profesor'));
    }

    public function edit(Profesor $profesor)
    {
        $profesor->load('user');
        return response()->json($profesor);
    }


    public function update(Request $request, $id)
    {
        $profesor = Profesor::with('user')->findOrFail($id);

        $data = $request->validate([
            'nombres' => 'required',
            'apellidos' => 'required',
            'telefono' => 'required',
            'email' => 'required|email|max:50|unique:users,email,' . $profesor->user_id,
            'password' => 'nullable|min:8|confirmed',
        ]);

        // Actualizar profesor
        $profesor->update([
            'nombres' => $data['nombres'],
            'apellidos' => $data['apellidos'],
            'telefono' => $data['telefono'],
        ]);

        // Actualizar usuario
        $usuario = $profesor->user;
        $usuario->email = $data['email'];

        if ($request->filled('password')) {
            $usuario->password = Hash::make($data['password']);
        }

        $usuario->save();

        return response()->json([
            'message' => 'Profesor actualizado correctamente',
            'profesor' => $profesor->load('user')
        ]);
    }


    public function destroy(Profesor $profesor)
    {   // Verificar si el profesor tiene agendas asociados
        if ($profesor->agendas()->exists()) {
            return redirect()->route('admin.profesores.index')->with('title', 'Error al eliminar profesor')
                ->with(['info', 'No se puede eliminar el profesor porque tiene agendas asociados.', 'icono', 'error']);
        }

        if ($profesor->user) {
            $profesor->user->delete();
        }
        $profesor->delete(); //// Eliminar el profesor y usuario asociado

        return redirect()->route('admin.profesores.index')
            ->with(['info', 'El profesor se eliminó con éxito', 'icono', 'success']);
    }


    public function obtenerProfesores($cursoId)
    {
        try {
            // Obtener los profesores asociados con el curso a través de la tabla intermedia
            $profesores = DB::table('horario_profesor_curso')
                ->join('profesors', 'horario_profesor_curso.profesor_id', '=', 'profesors.id')
                ->join('horarios', 'horario_profesor_curso.horario_id', '=', 'horarios.id')
                ->join('cursos', 'horario_profesor_curso.curso_id', '=', 'cursos.id') // Relacionar directamente la tabla intermedia con cursos
                ->where('cursos.id', $cursoId) // Filtrar por el ID del curso
                ->select('profesors.*')
                ->distinct()
                ->get();

            if ($profesores->isEmpty()) {
                return response()->json(['message' => 'No se encontraron profesores para este curso.'], 404);
            }

            return response()->json($profesores); // Devuelves la lista de profesores en formato JSON
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al cargar los profesores: ' . $e->getMessage()], 500);
        }
    }
    public function toggleStatus($id) //DEACTIVATE
    {
        $user = User::findOrFail($id);
        $user->status = !$user->status;
        $user->save();
    }
}
