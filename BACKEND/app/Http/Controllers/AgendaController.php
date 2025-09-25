<?php

namespace App\Http\Controllers;

use App\Helpers\DateHelper;

use App\Models\Asistencia;
use App\Models\Cliente;
use App\Models\Config;
use App\Models\Curso;
use App\Models\Profesor;
use App\Models\Agenda as CalendarAgenda;
use App\Models\Horario;
use App\Models\HorarioProfesorCurso;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AgendaController extends Controller
{
    public function __construct()
    {  // Solo los que tengan el permiso pueden acceder a estas acciones
        $this->middleware('can:admin.agendas.create')->only('create', 'store');
        $this->middleware('can:admin.agendas.edit')->only('edit', 'update');
        $this->middleware('can:admin.agendas.destroy')->only('destroy');
    }
    // public function index() {} public function create() {}

    public function store(Request $request)
    {   $request->validate([
            'cursoid' => 'required',
            'profesorid' => 'required|exists:profesors,id',
            'fecha_reserva' => 'required',
            'hora_inicio' => 'required',
            'hora_fin' => 'required|numeric|min:1', //'hora_fin' => 'required|date_format:H:i',
        ]);
        // Buscar el profesor por su ID
        $profesor = Profesor::find($request->profesorid);
        $fecha_reserva = $request->fecha_reserva;
        $hora_inicio = $request->hora_inicio . ':00';                          // Asegurarse de que la hora esté en formato correcto
        $fecha_hora_inicio = Carbon::parse("{$fecha_reserva} {$hora_inicio}");  // Crear un objeto Carbon para la fecha y hora de inicio
        $fecha_hora_fin = $fecha_hora_inicio->copy()->addHours($request->hora_fin); // Sumamos las horas ingresadas en el campo 'hora_fin'
        $cursoid = $request->cursoid;

        // Obtener el cliente para verificar asistencia
        $cliente_id = Auth::user()->hasRole('superAdmin') || Auth::user()->hasRole('admin') || Auth::user()->hasRole('secretaria') ? $request->cliente_id : Auth::user()->cliente->id;

        if (Auth::user()->hasRole('cliente')) {                 // Verificar si el cliente tiene un agenda anterior y si asistió
            $asistencia = Asistencia::join('agendas', 'asistencias.agenda_id', '=', 'agendas.id')
                ->select('agendas.start', 'asistencias.*')
                ->where('asistencias.cliente_id', $cliente_id)  // Filtrar por cliente
                ->orderBy('agendas.start', 'desc')->first();     // Ordenar por fecha para obtener el agenda más reciente

            if ($asistencia && $asistencia->asistio === 0) {     // Si hay asistencia y no asistió
                return redirect()->back()->with([
                    'info' => 'No puedes agendar otra clase hasta que contactes con la escuela por faltar a tu último agenda.',
                    'icono' => 'error','title' => 'Asistencia pendiente',
                ]);
            }
        }

        $dia = date('l', strtotime($fecha_reserva));                      // Obtener el día de la semana en español
        $dia_de_reserva = DateHelper::traducirDia($dia);
        
        $hora_inicio_formato = $fecha_hora_inicio->format('H:i:s');       // Formatear las horas para compararlas en la consulta
        $hora_fin_formato    = $fecha_hora_fin->format('H:i:s');

        // Consultar si el profesor tiene disponibilidad en el intervalo
        $horarios=HorarioProfesorCurso::join('horarios','horario_profesor_curso.horario_id','=','horarios.id') // Unimos con la tabla correcta
            ->where('horario_profesor_curso.profesor_id', $profesor->id)
            ->where('horarios.dia', $dia_de_reserva)                        // Filtrar por el día en la tabla correcta
            ->where('horarios.hora_inicio', '<=', $hora_inicio_formato)     // Ahora filtramos por horarios.hora_inicio
            ->where('horarios.hora_fin', '>=', $hora_fin_formato)           // Ahora filtramos por horarios.hora_fin
            ->where('horario_profesor_curso.curso_id', $cursoid)            // Si la tabla maneja cursos
            ->get();

        if ($horarios->isEmpty()) {                                         // Si no hay horarios disponibles, retornar mensaje de error
            return redirect()->back()->with([
                'icono' => 'error','title' => 'Oh!.',
                'info' => 'El profesor no está disponible en ese horario.',
            ]);
        }

        $agendas_duplicados = CalendarAgenda::where('profesor_id', $profesor->id)    // Validar si existen agendas duplicados
            ->where(function ($query) use ($fecha_hora_inicio, $fecha_hora_fin) {
                $query->whereBetween('start', [$fecha_hora_inicio, $fecha_hora_fin])
                    ->orWhereBetween('end', [$fecha_hora_inicio, $fecha_hora_fin]);
            })->exists();

        if ($agendas_duplicados) {
            return redirect()->back()->with(['info' => 'Ya existe una reserva con el mismo profesor en esa fecha y hora.', 'icono' => 'error', 'title' => 'Ya existe una reserva con el mismo profesor en esa fecha y hora.',]);
        }

        $curso = Curso::find($cursoid);
        // Crear una nueva instancia de CalendarAgenda
        $agenda = new CalendarAgenda();
        $agenda->title = $curso->nombre;
        $agenda->start = $fecha_hora_inicio;
        $agenda->end = $fecha_hora_fin;
        $agenda->color = '#e82216';
        $agenda->profesor_id = $request->profesorid;
        $agenda->curso_id = $cursoid;

        if (Auth::user()->hasRole('superAdmin') || Auth::user()->hasRole('admin') || Auth::user()->hasRole('secretaria')) {
                 $agenda->cliente_id = $request->cliente_id;
        } else { $agenda->cliente_id = Auth::user()->cliente->id; }

        $agenda->save();
        
        Asistencia::create([                            // Crear una asistencia por defecto como inasistencia
            'cliente_id' => $agenda->cliente_id,
            'agenda_id' => $agenda->id,
            'asistio' => false,                         // Inasistencia por defecto
            'penalidad' => 20000 * $request->hora_fin,  // Penalidad por inasistencia
            'liquidado' => false,
            'fecha_pago_multa' => null,
        ]);
        
        return redirect()->back()->with([               // Redirigir con un mensaje de éxito
            'swal'=>'1','info' => 'Recuerda que no puedes faltar a tu clase, si faltas a las clases sin justificación se cobran 20 mil pesos por hora no vista.',
            'icono' => 'success','title' => 'Se ha agendado de forma correcta.',
        ]);
    }

    public function show()
    {  try {
            $agendas = CalendarAgenda::with('profesor', 'cliente')->get(); // Carga la relación 'profesor'
            return response()->json($agendas); // Devuelve todos los agendas
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener agendas'], 500);
        }
    }

    public function update(Request $request, CalendarAgenda $agenda)
    {
        $validatedData = $request->validate(['profesor_id' => 'required', 'hora_inicio' => 'required', 'fecha_reserva' => 'required|date']);
        $agenda->update($validatedData);
        return response()->json(['message' => 'agenda actualizado correctamente']);
    }

    public function destroy(CalendarAgenda $agenda)
    {
        $agenda->delete();
        return redirect()->back()->with(['mensaje' => 'Se eliminó la reserva de manera correcta', 'icono' => 'success',]);
    }
    
    // private function traducir_dia($dia)
    // { $dias = ['Monday'=>'LUNES','Tuesday'=>'MARTES','Wednesday'=>'MIERCOLES','Thursday'=>'JUEVES','Friday'=>'VIERNES','Saturday'=>'SABADO','Sunday'=>'DOMINGO',];
    //     return $dias[$dia] ?? $dias; }
    ///================ [ NO SE ESTAN USANDO ]================

    // public function reportes(){
    //     return view('admin.reservas.reportes');
    // }

    // public function pdf()
    // {
    //     $configuracion = Config::latest()->first();
    //     $agendas = CalendarAgenda::all();

    //     $pdf = Pdf::loadView('admin.reservas.pdf', compact('configuracion', 'agendas'));

    //     // Incluir la numeración de páginas y el pie de página
    //     $pdf->output();
    //     $dompdf = $pdf->getDomPDF();
    //     $canvas = $dompdf->getCanvas();
    //     $canvas->page_text(20, 800, "Impreso por: " . Auth::user()->email, null, 10, array(0, 0, 0));
    //     $canvas->page_text(270, 800, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(0, 0, 0));
    //     $canvas->page_text(450, 800, "Fecha: " . \Carbon\Carbon::now()->format('d/m/Y') . " - " . \Carbon\Carbon::now()->format('H:i:s'), null, 10, array(0, 0, 0));


    //     return $pdf->stream();
    // }

    // public function pdf_fechas(Request $request){
    //     //$datos = request()->all();
    //     //return response()->json($datos);

    //     $configuracion = Configuracione::latest()->first();

    //     $fecha_inicio = $request->input('fecha_inicio');
    //     $fecha_fin = $request->input('fecha_fin');

    //     $agendas = CalendarAgenda::whereBetween('start',[$fecha_inicio, $fecha_fin])->get();

    //     $pdf = \PDF::loadView('admin.reservas.pdf_fechas', compact('configuracion','agendas','fecha_inicio','fecha_fin'));

    //     // Incluir la numeración de páginas y el pie de página
    //     $pdf->output();
    //     $dompdf = $pdf->getDomPDF();
    //     $canvas = $dompdf->getCanvas();
    //     $canvas->page_text(20, 800, "Impreso por: ".Auth::user()->email, null, 10, array(0,0,0));
    //     $canvas->page_text(270, 800, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(0,0,0));
    //     $canvas->page_text(450, 800, "Fecha: " . \Carbon\Carbon::now()->format('d/m/Y')." - ".\Carbon\Carbon::now()->format('H:i:s'), null, 10, array(0,0,0));


    //     return $pdf->stream();
    // }
}
