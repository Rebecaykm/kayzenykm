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
        User::create(['name' => 'Admin Test', 'email' => 'admin@admin.com', 'email_verified_at' => now(),'password' => bcrypt('123'), 'remember_token' => Str::random(10)])->assignRole('Administrator');
        User::create(['name' => 'It Test', 'email' => 'it@ykm.com.mx', 'email_verified_at' => now(), 'password' => bcrypt('123'), 'remember_token' => Str::random(10)])->assignRole('Administrator');
        User::create(['name' => 'User Test', 'email' => 'user@example.com', 'email_verified_at' => now(), 'password' => bcrypt('123'), 'remember_token' => Str::random(10)])->assignRole('User');
        User::create(['name' => 'Guest Test', 'email' => 'guest@example.com', 'email_verified_at' => now(), 'password' => bcrypt('123'), 'remember_token' => Str::random(10)])->assignRole('Guest');
        User::factory(11)->create();
    }
}
