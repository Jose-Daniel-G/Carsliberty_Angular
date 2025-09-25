<?php

namespace App\Http\Controllers;
use App\Models\Profesor;
use App\Models\TipoVehiculo;
use App\Models\Vehiculo;
use Illuminate\Http\Request;

class VehiculoController extends Controller
{
    public function __construct()
    {  // Solo los que tengan el permiso pueden acceder a estas acciones
        $this->middleware('can:admin.vehiculos.index')->only('index');
        $this->middleware('can:admin.vehiculos.create')->only('create', 'store');
        $this->middleware('can:admin.vehiculos.edit')->only('edit', 'update');
        $this->middleware('can:admin.vehiculos.destroy')->only('destroy');
    }
    
    public function index()
    {
        $vehiculos = Vehiculo::leftJoin('profesors', 'vehiculos.profesor_id', '=', 'profesors.id')
            ->select('vehiculos.*', 'profesors.nombres', 'profesors.apellidos')->get();
        // dd($vehiculos);
        $tipos = TipoVehiculo::all();
        $profesors = Profesor::all();
        return view("admin.vehiculos.index", compact('vehiculos', 'tipos', 'profesors'));
    }

    public function create() {}

    public function store(Request $request)
    {
        $vehiculos = $request->validate([
            'placa' => 'required|string|max:10|unique:vehiculos,placa', // Validación para que la placa sea única
            'modelo' => 'required|string|max:255',
            'tipo_id' => 'required|exists:tipos_vehiculos,id', // Asegúrate de que 'tipo' sea válido
            'disponible' => 'required|boolean', // Asumiendo que quieres manejar disponibilidad
            'profesor_id' => 'required|exists:users,id', // Asegúrate de que el usuario exista
        ]);
        // dd($vehiculos);

        Vehiculo::create($vehiculos);

        return redirect()->route('admin.vehiculos.index')
            ->with('title', 'Éxito')
            ->with('icon', 'success')
            ->with('info', 'Vehículo creado correctamente.');
    }

    public function show(Vehiculo $vehiculo)
    {
        // Cargar relaciones tipo y profesor
        $vehiculo->load(['tipo', 'profesor']);

        \Log::info('vehiculo', [$vehiculo]);

        return response()->json([ 'vehiculo' => $vehiculo ]);
    }


    public function edit(Vehiculo $vehiculo)
    {
        // Cargar el profesor y su user
        $vehiculo->load(['tipo', 'profesor']);
        
        $profesores = Profesor::all();
        \Log::info('profesores', [$profesores]);

        $tipos = TipoVehiculo::all();

        return response()->json([
            'vehiculo' => $vehiculo,
            'profesores' => $profesores,
            'tipos' => $tipos,
        ]);
    }



    public function update(Request $request, Vehiculo $vehiculo)
    {   
        // The unique validation rule is modified to ignore the current vehicle's ID
        $data = $request->validate([
            'placa' => 'required|string|max:7|unique:vehiculos,placa,' . $vehiculo->id,
            'modelo' => 'required|string|max:255',
            'tipo_selected' => 'required|exists:tipos_vehiculos,id',
            'disponible' => 'required|boolean',
            'profesor_id' => 'nullable|exists:profesors,id'
        ]);

        $data['tipo_id'] = $data['tipo_selected'];
        unset($data['tipo_selected']);

        $vehiculo->update($data);

        return redirect()->route('admin.vehiculos.index')
            ->with('title', 'Éxito')
            ->with('info', 'Vehículo actualizado correctamente.')
            ->with('icon', 'success');
    }


    public function destroy(Vehiculo $vehiculo)
    {
        $vehiculo->delete();

        return redirect()->route('admin.vehiculos.index')
            ->with('title', 'Éxito')
            ->with('info', 'El vehículo ha sido eliminado exitosamente.')
            ->with('icon', 'success');
    }
}
