<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
        public function run()
    {

        // ----------------------------------------------------------------------------------------------
        // Crear roles y asignar permisos
        $superAdmin = Role::create(['name' => 'superAdmin']);
        $admin = Role::create(['name' => 'admin']);
        $secretaria = Role::create(['name' => 'secretaria']);
        $profesor = Role::create(['name' => 'profesor']);
        $cliente = Role::create(['name' => 'cliente']);
        // ----------------------------------------------------------------------------------------------
        $espectador = Role::create(['name' => 'espectador']);
        //------------------------[ ALEJANDRO PROJECT  ]---------------------------------
        // Permission::create(['name'=>'admin.home'])->assignRole($admin);
        Permission::create(['name' => 'admin.home'])->syncRoles([$superAdmin, $admin, $secretaria, $profesor, $cliente]);
        Permission::create(['name' => 'admin.index']);

        // //rutas para el admin


        //rutas - configuraciones
        Permission::create(['name' => 'admin.config.index'])->syncRoles([$superAdmin]);
        Permission::create(['name' => 'admin.config.create'])->syncRoles([$superAdmin]);
        Permission::create(['name' => 'admin.config.store'])->syncRoles([$superAdmin]);
        Permission::create(['name' => 'admin.config.show'])->syncRoles([$superAdmin]);
        Permission::create(['name' => 'admin.config.edit'])->syncRoles([$superAdmin]);
        Permission::create(['name' => 'admin.config.destroy'])->syncRoles([$superAdmin]);

        //rutas para el admin - secretarias
        Permission::create(['name' => 'admin.secretarias.index'])->syncRoles([$superAdmin, $admin]);
        Permission::create(['name' => 'admin.secretarias.create'])->syncRoles([$superAdmin, $admin]);
        Permission::create(['name' => 'admin.secretarias.store'])->syncRoles([$superAdmin, $admin]);
        Permission::create(['name' => 'admin.secretarias.show'])->syncRoles([$superAdmin, $admin]);
        Permission::create(['name' => 'admin.secretarias.edit'])->syncRoles([$superAdmin, $admin]);
        Permission::create(['name' => 'admin.secretarias.destroy'])->syncRoles([$superAdmin, $admin]);

        //rutas para el admin - clientes
        Permission::create(['name' => 'admin.clientes.index'])->syncRoles([$superAdmin, $admin, $secretaria]);
        Permission::create(['name' => 'admin.clientes.create'])->syncRoles([$superAdmin, $admin, $secretaria]);
        Permission::create(['name' => 'admin.clientes.store'])->syncRoles([$superAdmin, $admin, $secretaria]);
        Permission::create(['name' => 'admin.clientes.show'])->syncRoles([$superAdmin, $admin, $secretaria]);
        Permission::create(['name' => 'admin.clientes.edit'])->syncRoles([$superAdmin, $admin, $secretaria]);
        Permission::create(['name' => 'admin.clientes.destroy'])->syncRoles([$superAdmin, $admin, $secretaria]);
        //rutas para el admin - cursos
        Permission::create(['name' => 'admin.cursos.index'])->syncRoles([$superAdmin, $admin, $secretaria]);
        Permission::create(['name' => 'admin.cursos.create'])->syncRoles([$superAdmin, $admin, $secretaria]);
        Permission::create(['name' => 'admin.cursos.store'])->syncRoles([$superAdmin, $admin, $secretaria]);
        Permission::create(['name' => 'admin.cursos.show'])->syncRoles([$superAdmin, $admin, $secretaria]);
        Permission::create(['name' => 'admin.cursos.edit'])->syncRoles([$superAdmin, $admin, $secretaria]);
        Permission::create(['name' => 'admin.cursos.destroy'])->syncRoles([$superAdmin, $admin, $secretaria]);
        //rutas para el admin - profesores
        Permission::create(['name' => 'admin.profesores.index'])->syncRoles([$superAdmin, $admin, $secretaria]);
        Permission::create(['name' => 'admin.profesores.create'])->syncRoles([$superAdmin, $admin, $secretaria]);
        Permission::create(['name' => 'admin.profesores.store'])->syncRoles([$superAdmin, $admin, $secretaria]);
        Permission::create(['name' => 'admin.profesores.show'])->syncRoles([$superAdmin, $admin, $secretaria]);
        Permission::create(['name' => 'admin.profesores.edit'])->syncRoles([$superAdmin, $admin, $secretaria]);
        Permission::create(['name' => 'admin.profesores.destroy'])->syncRoles([$superAdmin, $admin, $secretaria]);
        Permission::create(['name' => 'admin.profesores.pdf'])->syncRoles([$superAdmin, $admin, $secretaria]);
        Permission::create(['name' => 'admin.profesores.reportes'])->syncRoles([$superAdmin, $admin, $secretaria]);

        //rutas para el admin - horarios
        Permission::create(['name' => 'admin.horarios.index'])->syncRoles([$superAdmin, $admin, $secretaria]);
        Permission::create(['name' => 'admin.horarios.create'])->syncRoles([$superAdmin, $admin, $secretaria]);
        Permission::create(['name' => 'admin.horarios.store'])->syncRoles([$superAdmin, $admin, $secretaria]);
        Permission::create(['name' => 'admin.horarios.show'])->syncRoles([$superAdmin, $admin, $secretaria]);
        Permission::create(['name' => 'admin.horarios.edit'])->syncRoles([$superAdmin, $admin, $secretaria]);
        // rutas para EVENTOS
        Permission::create(['name' => 'admin.agendas.index'])->syncRoles([$superAdmin, $admin, $secretaria, $cliente]);
        Permission::create(['name' => 'admin.agendas.create'])->syncRoles([$superAdmin, $admin, $secretaria, $cliente]);
        Permission::create(['name' => 'admin.agendas.store'])->syncRoles([$superAdmin, $admin, $secretaria, $cliente]);
        Permission::create(['name' => 'admin.agendas.show'])->syncRoles([$superAdmin, $admin, $secretaria, $cliente]);
        Permission::create(['name' => 'admin.agendas.edit'])->syncRoles([$superAdmin, $admin, $secretaria]);
        Permission::create(['name' => 'admin.agendas.update'])->syncRoles([$superAdmin, $admin, $secretaria]);
        Permission::create(['name' => 'admin.agendas.destroy'])->syncRoles([$superAdmin, $admin, $secretaria]);
        // //rutas para el admin VEHICULOS
        Permission::create(['name' => 'admin.vehiculos.index'])->syncRoles([$superAdmin]);
        Permission::create(['name' => 'admin.vehiculos.create'])->syncRoles([$superAdmin]);
        Permission::create(['name' => 'admin.vehiculos.update'])->syncRoles([$superAdmin]);

        //rutas para el admin PICO Y PLACA
        Permission::create(['name' => 'admin.vehiculos.pico_y_placa.index'])->syncRoles([$superAdmin]);
        Permission::create(['name' => 'admin.vehiculos.pico_y_placa.create'])->syncRoles([$superAdmin]);
        Permission::create(['name' => 'admin.vehiculos.pico_y_placa.update'])->syncRoles([$superAdmin]);

        //----------------------------------------------------------------------------------------
        Permission::create(['name' => 'show_datos_cursos'])->syncRoles([$superAdmin, $admin, $secretaria, $cliente]);
        Permission::create(['name' => 'admin.horarios.show_reserva_profesores'])->syncRoles([$superAdmin, $admin, $secretaria]);
        Permission::create(['name' => 'admin.show_reservas'])->syncRoles([$superAdmin, $admin, $secretaria, $cliente]);

        Permission::create(['name' => 'admin.listUsers'])->syncRoles([$superAdmin, $admin, $secretaria]);
        Permission::create(['name' => 'admin.reservas.edit'])->syncRoles([$superAdmin, $admin, $secretaria]);
        //rutas para el admin - asistencias
        Permission::create(['name' => 'admin.asistencias.index'])->syncRoles([$superAdmin, $admin, $secretaria, $profesor]);
        Permission::create(['name' => 'admin.asistencias.inasistencias'])->syncRoles([$superAdmin, $admin, $secretaria]);
        //rutas para el admin - horarios
        Permission::create(['name' => 'admin.horarios'])->syncRoles([$superAdmin, $admin, $secretaria]);
        // $superAdmin->givePermissionTo(Permission::all());
        //----------------------------------------------------------------------------------------
        //PERMISSIONS ROUTES
        Permission::create(['name' => 'permissions.index'])->syncRoles([$superAdmin]);
        Permission::create(['name' => 'permissions.create'])->syncRoles([$superAdmin]);
        Permission::create(['name' => 'permissions.edit'])->syncRoles([$superAdmin]);
        Permission::create(['name' => 'permissions.delete'])->syncRoles([$superAdmin]);
        //ROLES ROUTES
        Permission::create(['name' => 'roles.index'])->syncRoles([$superAdmin]);
        Permission::create(['name' => 'roles.create'])->syncRoles([$superAdmin]);
        Permission::create(['name' => 'roles.edit'])->syncRoles([$superAdmin]);
        Permission::create(['name' => 'roles.destroy'])->syncRoles([$superAdmin]);
        //----------------------------------------------------------------------------------------

        // Permission::create(['name' => 'admin.users.index'])->syncRoles([$superAdmin, $admin]);
        // //proximamente remplazadas estas rutas seran
        // Permission::create(['name' => 'admin.usuarios.index'])->syncRoles([$superAdmin, $admin]);
        // Permission::create(['name' => 'admin.usuarios.create'])->syncRoles([$superAdmin, $admin]);
        // Permission::create(['name' => 'admin.usuarios.store'])->syncRoles([$superAdmin, $admin]);
        // Permission::create(['name' => 'admin.usuarios.show'])->syncRoles([$superAdmin, $admin]);
        // Permission::create(['name' => 'admin.usuarios.edit'])->syncRoles([$superAdmin, $admin]);
        // Permission::create(['name' => 'admin.users.update'])->syncRoles([$superAdmin, $admin]);
        // Permission::create(['name' => 'admin.usuarios.destroy'])->syncRoles([$superAdmin, $admin]);

        // $admin->permissions()->attach();
    }
    // MULTIMEDIA CAM
    // public function run()
    // {
    //     $role1 = Role::create(['name' => 'admin']);
    //     $role2 = Role::create(['name' => 'funcionario']);
    //     $role4 = Role::create(['name' => 'usuario']);

    //     // Permission::create(['name'=>'home'])->assignRole($role1);
    //     Permission::create(['name' => 'home'])->syncRoles([$role1, $role2, $role4]);
    //     Permission::create(['name' => 'users.index'])->syncRoles([$role1]);
    //     Permission::create(['name' => 'users.create'])->syncRoles([$role1]);
    //     Permission::create(['name' => 'users.update'])->syncRoles([$role1]);
        
    //     //PERMISSIONS ROUTES
    //     Permission::create(['name' => 'permissions.index'])->syncRoles([$role1]);
    //     Permission::create(['name' => 'permissions.create'])->syncRoles([$role1]);
    //     Permission::create(['name' => 'permissions.edit'])->syncRoles([$role1]);
    //     Permission::create(['name' => 'permissions.delete'])->syncRoles([$role1]);

    //     //NOTIFICACION ROUTES
    //     Permission::create(['name' => 'notificacion.index'])->syncRoles([$role1, $role2]);
    //     Permission::create(['name' => 'notificacion.create'])->syncRoles([$role1, $role2]);
    //     Permission::create(['name' => 'notificacion.edit'])->syncRoles([$role1, $role2]);
    //     Permission::create(['name' => 'notificacion.destroy'])->syncRoles([$role1, $role2]);
    //     //ROLES ROUTES
    //     Permission::create(['name' => 'roles.index'])->syncRoles([$role1, $role2]);
    //     Permission::create(['name' => 'roles.create'])->syncRoles([$role1, $role2]);
    //     Permission::create(['name' => 'roles.edit'])->syncRoles([$role1, $role2]);
    //     Permission::create(['name' => 'roles.destroy'])->syncRoles([$role1, $role2]);
    // }
}
