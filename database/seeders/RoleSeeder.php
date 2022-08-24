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
        $adminRole = Role::create(['name' => 'Administrator']);
        $userRole = Role::create(['name' => 'User']);
        $guestRole = Role::create(['name' => 'Guest']);

        Permission::create(['name' => 'dashboard'])->syncRoles([$adminRole, $userRole, $guestRole]);

        Permission::create(['name' => 'users.index'])->syncRoles([$adminRole]);
        Permission::create(['name' => 'users.create'])->syncRoles([$adminRole]);
        Permission::create(['name' => 'users.store'])->syncRoles([$adminRole]);
        Permission::create(['name' => 'users.edit'])->syncRoles([$adminRole]);
        Permission::create(['name' => 'users.update'])->syncRoles([$adminRole]);
        Permission::create(['name' => 'users.destroy'])->syncRoles([$adminRole]);

        Permission::create(['name' => 'open-orders.index'])->syncRoles([$adminRole, $userRole]);
        Permission::create(['name' => 'open-orders.store'])->syncRoles([$adminRole, $userRole]);

        Permission::create(['name' => 'daily-production.index'])->syncRoles([$adminRole, $userRole]);
        Permission::create(['name' => 'daily-production.user'])->syncRoles([$adminRole, $guestRole]);
        Permission::create(['name' => 'daily-production.store'])->syncRoles([$adminRole, $userRole]);
    }
}
