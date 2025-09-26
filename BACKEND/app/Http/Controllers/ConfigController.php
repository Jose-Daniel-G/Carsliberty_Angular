<?php

namespace App\Http\Controllers;

use App\Models\Config;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ConfigController extends Controller
{
    public function __construct()
    {  // Solo los que tengan el permiso pueden acceder a estas acciones
        // $this->middleware('can:admin.config.index')->only('index');
        // $this->middleware('can:admin.config.create')->only('create', 'store');
        // $this->middleware('can:admin.config.edit')->only('edit', 'update');
        // $this->middleware('can:admin.config.destroy')->only('destroy');
    }

    public function index()
    {
        $config = Config::first(); // Obtén la primera fila de la tabla de configuración
        return view('admin.config.index', compact('config'));
    }
    // public function create()  {  return view('admin.config.create'); }
    public function store(Request $request)
    { // dd($request->all());
        $request->validate([
            'site_name'    => 'required|string',
            'email_contact'    => 'required|email',
            'address' => 'required|string|max:255',
            'phone'  => 'required|numeric',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg',
        ]);
        // Crear una nueva instancia del modelo Config
        $config = new Config();
        $config->site_name = $request->site_name;
        $config->email_contact = $request->email_contact;
        $config->address = $request->address;
        $config->phone = $request->phone;
        $config->save();

        // Manejo de archivo logo si se ha subido
        if ($request->hasFile('logo')) {
            $file     = $request->file('logo');
            $nombre = time() . "_" . $file->getClientOriginalName();
            $ruta = $file->storeAs('logo', $nombre);
            $url = 'storage/' . $ruta;
            $config->logo = $url;
            $config->save();

            $imagen_id = $config->getKey();

            Image::create(['url' => $url, 'imageable_id' => $imagen_id, 'imageable_type' => Config::class]); // $post->image()->create(['url' => $url]);
        }

        return redirect()->route('admin.config.index')->with(['title', 'Exito', 'info', 'Configuración creada', 'icono', 'success']);
    }

    public function edit(Config $config)
    {
        return view('admin.config.edit', compact('config'));
    }

    public function update(Request $request, Config $config)
    {
        $request->validate([
            'site_name'    => 'required|string',
            'email_contact'    => 'required|email',
            'address' => 'required|string|max:255',
            'phone'  => 'required|numeric',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg',
        ]);

        // Asignación de los datos al modelo
        $config->site_name = $request->site_name;
        $config->email_contact = $request->email_contact;
        $config->address = $request->address;
        $config->phone = $request->phone;

        if ($request->hasFile('logo')) { // Eliminar el logo anterior si existe
            if ($config->logo) {
                Storage::delete('public/' . $config->logo);
            }
            $logoPath = $request->file('logo')->store('logos', 'public');
            $config->logo = $logoPath;
        }
        $config->save();

        return redirect()->route('admin.config.index')->with(['title', 'Exito', 'icono', 'success', 'info', 'Configuración actualizada exitosamente']);
    }

    public function destroy(Config $config) // Eliminar el logo si existe
    {

        if ($config->image) {              // Borrar imagen asociada
            $path = str_replace('storage/', '', $config->image->url);
            Storage::disk('public')->delete($path);
            $config->image->delete();
        }

        $config->delete();  // Eliminar la configuración

        return redirect()->route('admin.config.index')->with(['title', 'Exito', 'icono', 'success', 'info', 'Configuración eliminada correctamente']);
    }
}
