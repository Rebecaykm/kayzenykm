<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create(['name' => 'Usuario Administrador', 'email' => 'admin@admin.com', 'email_verified_at' => now(),'password' => bcrypt('123'), 'remember_token' => Str::random(10)])->assignRole('Administrador');
        User::create(['name' => 'Usuario IT', 'email' => 'it@ykm.com.mx', 'email_verified_at' => now(), 'password' => bcrypt('123'), 'remember_token' => Str::random(10)])->assignRole('Administrador');
        User::create(['name' => 'Usuario Lider', 'email' => 'user@example.com', 'email_verified_at' => now(), 'password' => bcrypt('123'), 'remember_token' => Str::random(10)])->assignRole('Usuario');
        User::create(['name' => 'Usuario Linea', 'email' => 'guest@example.com', 'email_verified_at' => now(), 'password' => bcrypt('123'), 'remember_token' => Str::random(10)])->assignRole('Invitado');
    }
}
