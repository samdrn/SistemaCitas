<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'api']);

        $medicoRole = Role::firstOrCreate(['name' => 'medico', 'guard_name' => 'api']);

        $asistenteRole = Role::firstOrCreate(['name' => 'asistente', 'guard_name' => 'api']);


        $admin = User::firstOrCreate(['email' => 'admin@example.com',
        ], [
            'name' => 'admin',
            'password' => bcrypt('password'),
        ]);
 
        $medicoUser = User::firstOrCreate(['email' => 'medico@example.com',
        ], [
            'name' => 'medico',
            'password' => bcrypt('password'),
        ]);

        
        $asistenteUser = User::firstOrCreate(['email' => 'asistente@example.com',
        ], [
            'name' => 'asistente',
            'password' => bcrypt('password'),
        ]);

        $admin->assignRole($adminRole);
        $medicoUser->assignRole($medicoRole);
        $asistenteUser->assignRole($asistenteRole);
    }
}
