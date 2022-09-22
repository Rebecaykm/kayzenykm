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
        $guestRole = Role::create(['name' => 'Invitado']);

        Permission::create(['name' => 'dashboard', 'description' => 'Dashboard'])->syncRoles([$adminRole, $userRole, $guestRole]);

        Permission::create(['name' => 'users.index', 'description' => 'Usuarios'])->syncRoles([$adminRole]);
        Permission::create(['name' => 'roles.index', 'description' => 'Roles'])->syncRoles([$adminRole]);
        Permission::create(['name' => 'open-orders.index', 'description' => 'Informe de Órdenes Abiertas'])->syncRoles([$adminRole, $userRole]);
        Permission::create(['name' => 'daily-production.index', 'description' => 'Planificación y Progreso Diario de la Producción (Admin)'])->syncRoles([$adminRole, $userRole]);
        Permission::create(['name' => 'daily-production.user', 'description' => 'Planificación y Progreso Diario de la Producción (Usuario)'])->syncRoles([$adminRole, $guestRole]);
        Permission::create(['name' => 'planeacion.index', 'description' => 'Planning'])->syncRoles([$adminRole, $userRole]);

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
