<?php 
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; 

class PermissionController extends Controller 
{
    public function __construct()
    {
        return [
            $this->middleware('permission:permissions.index')->only('index'),
            $this->middleware('permission:permissions.edit')->only('edit'),
            $this->middleware('permission:permissions.create')->only('create'),
            $this->middleware('permission:permissions.delete')->only('destroy'),
        ];
    }
    public function index()
    {
        $permissions = Permission::orderBy('created_at', 'DESC')->get();
        return view('admin.permissions.index', compact('permissions'));
    }
 
    // public function create(){return view('admin.permissions.create');}
 
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), ['name' => 'required|unique:permissions|min:3']);

        if ($validator->passes()) {
            Permission::create(['name' => $request->name,'guard_name' => 'web',]);
            return redirect()->route('admin.permissions.index')->with('success', 'Permiso añadido exitosamente');
        } else {
            return redirect()->route('admin.permissions.index')->withInput()->withErrors($validator);
        }
    }

    // public function show(Permission $permission) { return view('admin.permissions.create'); }

    public function edit($id)
    {
        $permission = Permission::findOrfail($id);
        return response()->json(['permission' => $permission]);// return view('admin.permissions.edit', compact('permission'));
    }

    public function update(Request $request, $id)
    {
        $permission = Permission::findOrfail($id);

        $validator = Validator::make($request->all(), ['name' =>'required|min:3|unique:permissions,name,'.$id.',id']);
        if ($validator->passes()) {
            $permission->name = $request->name;
            $permission->guard_name ='web'; // <- necesario para evitar el error

            $permission->save();
            return redirect()->route('admin.permissions.index')->with('success','Permiso Actualizado exitosamente.');
        } else {
            return redirect()->route('admin.permissions.edit',$id)->withInput()->withErrors($validator);
        }
    }

    public function destroy(Request $request) {
        $id = $request->id;
    
        $permission = Permission::find($id);
    
        if ($permission == null) {
            session()->flash('error', 'Permission not found');
            return response()->json([
                'status' => false
            ]);
        }
    
        $permission->delete();
    
        session()->flash('success', 'Permission deleted successfully');
        return redirect()->back()->with('success', 'Permiso eliminado exitosamente.');
    }
}
