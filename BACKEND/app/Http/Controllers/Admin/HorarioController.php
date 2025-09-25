<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Helpers\DateHelper;
use App\Models\Curso;
use App\Models\Profesor;
use App\Models\Horario;
use App\Models\Agenda as CalendarAgenda;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HorarioController extends Controller
{
    public function __construct()
    {  // Solo los que tengan el permiso pueden acceder a estas acciones
        $this->middleware('can:admin.horarios')->only('index'); 
    }
    public function index()
    {
        $cursos = Curso::all();
        $horarios = Horario::with('profesores', 'cursos')->get(); // viene con la relacion del horario
        return view('admin.horarios.index', compact('horarios', 'cursos'));
    }

    public function create()
    {
        $profesores = Profesor::all();
        $cursos = Curso::all();
        $horarios = Horario::with('profesores', 'cursos')->get(); // viene con la relacion del horario
        return view('admin.horarios.create', compact('profesores', 'cursos', 'horarios'));
    }

    public function show_datos_cursos($id) //show datatable schedules for the teachers
    {
        try {
            // Obtener los cursos asignados al profesor
            $cursos_profesor = Curso::whereHas('horarios', function ($query) use ($id) {
                $query->whereHas('profesores', function ($query) use ($id) {
                    $query->where('profesor_id', $id);
                });
            })->with('horarios.profesores')->get();


            // Obtener horarios disponibles del profesor con sus cursos
            $horarios = Horario::whereHas('profesores', function ($query) use ($id) {
                $query->where('profesor_id', $id);
            })->with(['cursos', 'profesores'])->get();
            // Obtener Agenda agendados para este profesor
            $horarios_asignados = DB::table('agendas')
                ->select([
                    'agendas.id AS agenda_id',
                    'agendas.profesor_id',
                    'agendas.curso_id',
                    'agendas.start AS hora_inicio',
                    'agendas.end AS hora_fin',
                    DB::raw('DAYNAME(agendas.start) AS dia'),
                    'users.id AS user_id',
                    'users.name AS user_nombre',
                    'cursos.nombre AS curso_nombre'
                ])
                ->join('cursos', 'agendas.curso_id', '=', 'cursos.id')
                ->join('clientes', 'agendas.cliente_id', '=', 'clientes.id')
                ->join('users', 'clientes.user_id', '=', 'users.id')
                ->where('agendas.profesor_id', $id)
                ->get();
            // dd(['titulo' => 'Datos de horarios asignados', 'horarios asignados' => $horarios_asignados->toArray()]);

            // Traducir los días al español
            $horarios_asignados = $horarios_asignados->map(function ($horario) {
                $horario->dia = DateHelper::traducirDia($horario->dia);
                return $horario;
            });

            return view('admin.horarios.show_datos_cursos', compact('cursos_profesor', 'horarios', 'horarios_asignados'));
        } catch (\Exception $exception) {
            return response()->json(['mensaje' => 'Error', 'detalle' => $exception->getMessage()]);
        }
    }


    public function store(Request $request) //teacher schedule
    {
        // Validar los datos
        $validatedData = $request->validate([
            'dia' => 'required',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'profesor_id' => 'required|exists:profesors,id',
            'cursos' => 'required|array|min:1', // Al menos 1 curso
            'cursos.*' => 'exists:cursos,id',
        ]);

        // Normalizar el formato de las horas (si en la BD se guarda con segundos, por ejemplo "H:i:s")
        $horaInicio = Carbon::parse($validatedData['hora_inicio'])->format('H:i:s');
        $horaFin    = Carbon::parse($validatedData['hora_fin'])->format('H:i:s');

        // Verificar si el profesor ya tiene un horario agendado en ese día con superposición en el rango de horas
        $horarioExistente = Horario::where('dia', $validatedData['dia'])
            ->where('profesor_id', $validatedData['profesor_id'])
            ->where(function ($query) use ($horaInicio, $horaFin) {
                $query->where(function ($query) use ($horaInicio, $horaFin) {
                    $query->where('hora_inicio', '>=', $horaInicio)
                        ->where('hora_inicio', '<', $horaFin);
                })
                    ->orWhere(function ($query) use ($horaInicio, $horaFin) {
                        $query->where('hora_fin', '>', $horaInicio)
                            ->where('hora_fin', '<=', $horaFin);
                    })
                    ->orWhere(function ($query) use ($horaInicio, $horaFin) {
                        $query->where('hora_inicio', '<', $horaInicio)
                            ->where('hora_fin', '>', $horaFin);
                    });
            })
            ->exists();
        // dd($horarioExistente);
        if ($horarioExistente) {

            return redirect()->back()
                ->withInput()
                ->with('info', 'El profesor ya tiene asignado un horario en ese rango de tiempo.')
                ->with('icono', 'error');
        }
        // 
        try {
            // Si no existe un horario en ese rango, se crea el horario
            $horario = Horario::firstOrCreate([
                'dia' => $validatedData['dia'],
                'hora_inicio' => $horaInicio,
                'hora_fin' => $horaFin,
                'profesor_id' => $validatedData['profesor_id'],
            ]);

            // Se asocian los cursos a ese horario
            foreach ($validatedData['cursos'] as $cursoId) {
                DB::table('horario_profesor_curso')->updateOrInsert([
                    'horario_id' => $horario->id,
                    'curso_id' => $cursoId,
                    'profesor_id' => $validatedData['profesor_id']
                ]);
            }

            return redirect()->route('admin.horarios.create')
                ->with('info', 'Se registraron los cursos para el horario correctamente.')
                ->with('icono', 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Ocurrió un error al registrar el horario.');
        }
    }

    public function show(Horario $horario)
    {
        $horario->load('profesores', 'cursos'); // Cargar relaciones en la instancia
        // dd($horario);
        return view('admin.horarios.show', compact('horario'));
    }

    public function edit(Horario $horario)
    {
        $horario->load(['profesores', 'cursos']); // Cargar relaciones
        $profesores = Profesor::all();
        $cursos = Curso::all();
        // \Log::info('horario',[$horario]);
        // return view('admin.horarios.edit', compact('horario', 'curso', 'profesores', 'cursos'));'curso' => $curso,
        return response()->json(['horario' =>  $horario->toArray(),  'profesores' => $profesores, 'cursos' => $cursos]);
    }

    public function update(Request $request, Horario $horario)
    {   //dd($horario);
        $validatedData = $request->validate([
            'dia' => 'required',
            'hora_inicio' => 'required',
            'hora_fin' => 'required',
            'curso_id' => 'required',
        ]);
        // Actualizar datos propios del horario
        $horario->update([
            'dia' => $request->dia,
            'hora_inicio' => $request->hora_inicio,
            'hora_fin' => $request->hora_fin,
        ]);

        $horario->profesores()->syncWithPivotValues(
            [$request->profesor_id],
            ['curso_id' => $request->curso_id]
        );

        return redirect()->route('admin.horarios.index')
            ->with(['info', 'Horario actualizado correctamente.', 'icono', 'success']);
    }


    public function destroy(Horario $horario)
    {
        $horario->delete();
        return redirect()->route('admin.horarios.index')->with(['title', 'Exito', 'info', 'El horario se eliminó con éxito', 'icon', 'success']);
    }
}
