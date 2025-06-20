<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Employee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Admin::latest()->first();
        $positions = collect(['staff', 'manager']);

        Employee::create([
            'username' => 'raka',
            'password' => Hash::make('password'),
            'name' => 'Raka Febrian',
            'admin_id' => $admin->id,
            'position' => 'staff',
        ]);
        Employee::create([
            'username' => 'ethan',
            'password' => Hash::make('password'),
            'name' => 'Ethan Hunt',
            'admin_id' => $admin->id,
            'position' => 'manager',
        ]);
        Employee::create([
            'username' => 'joseph',
            'password' => Hash::make('password'),
            'name' => 'Joseph Goebbels',
            'admin_id' => $admin->id,
            'position' => 'chief',
        ]);

        for ($i = 0; $i < 0; $i++) {
            Employee::create([
                'username' => fake()->userName,
                'password' => Hash::make('password'),
                'name' => fake()->name,
                'admin_id' => $admin->id,
                'position' => $positions->random(),
            ]);
        }
    }
}
