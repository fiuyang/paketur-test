<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Employee;
use App\Models\Role;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'super_admin'],
            ['name' => 'manager'],
            ['name' => 'employee']
        ];
        Role::insert($roles);

        $superAdminRole = Role::where('name', 'super_admin')->first();
        $managerRole = Role::where('name', 'manager')->first();
        $employeeRole = Role::where('name', 'employee')->first();

        User::insert([
            [
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('password'),
                'role_id' => $superAdminRole->id
            ],
            [
                'name' => 'manager',
                'email' => 'manager@gmail.com',
                'password' => bcrypt('password'),
                'role_id' => $managerRole->id
            ],
            [
                'name' => 'employee',
                'email' => 'employee@gmail.com',
                'password' => bcrypt('password'),
                'role_id' => $employeeRole->id
            ]
        ]);

        Company::factory(20)->create();
        Employee::factory(20)->create();
    }
}
