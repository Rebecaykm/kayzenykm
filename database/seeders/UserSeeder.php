<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create(['name' => 'Usuario Administrador', 'email' => 'admin@admin.com', 'email_verified_at' => now(), 'password' => bcrypt('123'), 'remember_token' => Str::random(10)])->assignRole('Administrador');
        $it = User::create(['name' => 'Usuario IT', 'email' => 'it@ykm.com.mx', 'email_verified_at' => now(), 'password' => bcrypt('123'), 'remember_token' => Str::random(10)])->assignRole('Administrador');
        $planner = User::create(['name' => 'Rafael Nieto', 'email' => 'rafael.nieto@ykm.com.mx', 'email_verified_at' => now(), 'password' => bcrypt('Rani1123'), 'remember_token' => Str::random(10)])->assignRole('Usuario');
        $press = User::create(['name' => 'Lider Estampado', 'email' => 'press@example.com', 'email_verified_at' => now(), 'password' => bcrypt('123'), 'remember_token' => Str::random(10)])->assignRole('Lider');
        $body = User::create(['name' => 'Lider Carrocería', 'email' => 'body@example.com', 'email_verified_at' => now(), 'password' => bcrypt('123'), 'remember_token' => Str::random(10)])->assignRole('Lider');
        $chassis = User::create(['name' => 'Lider Chasis', 'email' => 'chassis@example.com', 'email_verified_at' => now(), 'password' => bcrypt('123'), 'remember_token' => Str::random(10)])->assignRole('Lider');
        $paint = User::create(['name' => 'Lider Pintura', 'email' => 'paint@example.com', 'email_verified_at' => now(), 'password' => bcrypt('123'), 'remember_token' => Str::random(10)])->assignRole('Lider');
        $vendor = User::create(['name' => 'Lider Proveedor', 'email' => 'vendor@example.com', 'email_verified_at' => now(), 'password' => bcrypt('123'), 'remember_token' => Str::random(10)])->assignRole('Lider');
        $operador = User::create(['name' => 'Operador', 'email' => 'guest@example.com', 'email_verified_at' => now(), 'password' => bcrypt('123'), 'remember_token' => Str::random(10)])->assignRole('Operador');

        $admin->departaments()->sync([1, 2, 3, 4, 5]);

        $it->departaments()->sync([1, 2, 3, 4, 5]);
        $press->departaments()->sync([1]);
        $body->departaments()->sync([2]);
        $chassis->departaments()->sync([3]);
        $paint->departaments()->sync([4]);
        $vendor->departaments()->sync([5]);

        $operador->departaments()->sync([2]);
    }
}
