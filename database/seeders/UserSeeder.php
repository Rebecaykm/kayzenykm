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
        $press = User::create(['name' => 'Líder Estampado', 'email' => 'press@example.com', 'email_verified_at' => now(), 'password' => bcrypt('123'), 'remember_token' => Str::random(10)])->assignRole('Usuario');
        $body = User::create(['name' => 'Líder Carrocería', 'email' => 'body@example.com', 'email_verified_at' => now(), 'password' => bcrypt('123'), 'remember_token' => Str::random(10)])->assignRole('Usuario');
        $chassis = User::create(['name' => 'Líder Chasis', 'email' => 'chassis@example.com', 'email_verified_at' => now(), 'password' => bcrypt('123'), 'remember_token' => Str::random(10)])->assignRole('Usuario');
        $paint = User::create(['name' => 'Líder Pintura', 'email' => 'paint@example.com', 'email_verified_at' => now(), 'password' => bcrypt('123'), 'remember_token' => Str::random(10)])->assignRole('Usuario');
        $vendor = User::create(['name' => 'Líder Proveedor', 'email' => 'vendor@example.com', 'email_verified_at' => now(), 'password' => bcrypt('123'), 'remember_token' => Str::random(10)])->assignRole('Usuario');
        $line = User::create(['name' => 'Usuario Línea', 'email' => 'guest@example.com', 'email_verified_at' => now(), 'password' => bcrypt('123'), 'remember_token' => Str::random(10)])->assignRole('Invitado');

        $admin->departaments()->sync([1, 2, 3, 4, 5]);

        $it->departaments()->sync([1, 2, 3, 4, 5]);
        $press->departaments()->sync([1]);
        $body->departaments()->sync([2]);
        $chassis->departaments()->sync([2]);
        $paint->departaments()->sync([4]);
        $vendor->departaments()->sync([5]);

        $line->departaments()->sync([4]);
    }
}
