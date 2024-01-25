<?php

namespace Database\Seeders;

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
        $adminRole = Role::create(['name' => 'Administrador']);
        $userRole = Role::create(['name' => 'Usuario']);
        $leaderRole = Role::create(['name' => 'Lider']);
        $guestRole = Role::create(['name' => 'Operador']);

        Permission::create(['name' => 'dashboard', 'description' => 'Dashboard'])->syncRoles([$adminRole, $userRole, $guestRole, $leaderRole]);

        Permission::create(['name' => 'users.index', 'description' => 'Usuarios'])->syncRoles([$adminRole]);
        Permission::create(['name' => 'permissions.index', 'description' => 'Permisos'])->syncRoles([$adminRole]);
        Permission::create(['name' => 'roles.index', 'description' => 'Roles'])->syncRoles([$adminRole]);
        Permission::create(['name' => 'transaction-type.index', 'description' => 'Tipo de Transacciones'])->syncRoles([$adminRole]);

        Permission::create(['name' => 'planeacion.index', 'description' => 'Listado de Plan'])->syncRoles([$adminRole, $userRole]);
        Permission::create(['name' => 'Structure.index', 'description' => 'Listado de Estructura'])->syncRoles([$adminRole]); //
        Permission::create(['name' => 'ShowStructure.index', 'description' => 'Muestrar la Estructura'])->syncRoles([$adminRole]); //
        Permission::create(['name' => 'daily-production.index', 'description' => 'Planificación y Progreso Diario de la Producción (Admin)']); //
        Permission::create(['name' => 'daily-production.user', 'description' => 'Planificación y Progreso Diario de la Producción (Usuario)']); //
        Permission::create(['name' => 'open-orders.index', 'description' => 'Informe de Órdenes Abiertas']); //

        Permission::create(['name' => 'measurement.index', 'description' => 'Unidad de Medida'])->syncRoles([$adminRole]);
        Permission::create(['name' => 'type.index', 'description' => 'Tipo de Item'])->syncRoles([$adminRole]);
        Permission::create(['name' => 'item-class.index', 'description' => 'Tipo de Clase'])->syncRoles([$adminRole]);
        Permission::create(['name' => 'standard-package.index', 'description' => 'Contenedor'])->syncRoles([$adminRole]);
        Permission::create(['name' => 'planner.index', 'description' => 'Codigo de Planeador'])->syncRoles([$adminRole]);
        Permission::create(['name' => 'client.index', 'description' => 'Cliente'])->syncRoles([$adminRole]);
        Permission::create(['name' => 'project.index', 'description' => 'Proyecto'])->syncRoles([$adminRole]);
        Permission::create(['name' => 'workcenter.index', 'description' => 'Estación de Trabajo'])->syncRoles([$adminRole]);
        Permission::create(['name' => 'part-number.index', 'description' => 'Número de Parte'])->syncRoles([$adminRole]);
        Permission::create(['name' => 'status.index', 'description' => 'Estados'])->syncRoles([$adminRole]);
        Permission::create(['name' => 'shift.index', 'description' => 'Turnos'])->syncRoles([$adminRole]);
        Permission::create(['name' => 'type-scrap.index', 'description' => 'Tipo de Scrap'])->syncRoles([$adminRole]);
        Permission::create(['name' => 'scrap.index', 'description' => 'Scrap'])->syncRoles([$adminRole]);
        Permission::create(['name' => 'unemployment-type.index', 'description' => 'Tipo de Paro'])->syncRoles([$adminRole]);
        Permission::create(['name' => 'unemployment.index', 'description' => 'Paro'])->syncRoles([$adminRole]);
        Permission::create(['name' => 'unemployment-record.index', 'description' => 'Registro de Paro'])->syncRoles([$adminRole, $leaderRole]);
        Permission::create(['name' => 'scrap-record.index', 'description' => 'Registro de Scrap'])->syncRoles([$adminRole, $leaderRole]);
        Permission::create(['name' => 'production-plan.index', 'description' => 'Plan de Producción'])->syncRoles([$adminRole, $leaderRole, $guestRole]);
        Permission::create(['name' => 'prodcution-record.index', 'description' => 'Registro de Producción'])->syncRoles([$adminRole, $leaderRole]);




        // Permission::create(['name' => 'users.index', 'description' => 'View list of Users'])->syncRoles([$adminRole]);
        // Permission::create(['name' => 'users.create', 'description' => 'Create User'])->syncRoles([$adminRole]);
        // Permission::create(['name' => 'users.edit', 'description' => 'Edit User'])->syncRoles([$adminRole]);
        // Permission::create(['name' => 'users.destroy', 'description' => 'Delete User'])->syncRoles([$adminRole]);

        // Permission::create(['name' => 'roles.index', 'description' => 'View List of Roles'])->syncRoles([$adminRole]);
        // Permission::create(['name' => 'roles.create', 'description' => 'Create Role'])->syncRoles([$adminRole]);
        // Permission::create(['name' => 'roles.edit', 'description' => 'Edit Role'])->syncRoles([$adminRole]);
        // Permission::create(['name' => 'roles.destroy', 'description' => 'Delete Role'])->syncRoles([$adminRole]);

        // Permission::create(['name' => 'open-orders.index', 'description' => 'View list of Open Orders'])->syncRoles([$adminRole, $userRole]);
        // Permission::create(['name' => 'open-orders.store', 'description' => 'Update Open Order Data'])->syncRoles([$adminRole, $userRole]);

        // Permission::create(['name' => 'daily-production.index', 'description' => 'View list of Daily Production as Administrator'])->syncRoles([$adminRole, $userRole]);
        // Permission::create(['name' => 'daily-production.user', 'description' => 'View list of Daily Production as User'])->syncRoles([$adminRole, $guestRole]);
        // Permission::create(['name' => 'daily-production.store', 'description' => 'Update Daily Production Data'])->syncRoles([$adminRole, $userRole]);

        // Permission::create(['name' => 'planeacion.index', 'description' => 'Index Planning'])->syncRoles([$adminRole, $userRole]);
        // Permission::create(['name' => 'planeacion.create', 'description' => 'Create Planning'])->syncRoles([$adminRole, $guestRole]);
        // Permission::create(['name' => 'planeacion.update', 'description' => 'Update Planning'])->syncRoles([$adminRole, $userRole]);
    }
}
