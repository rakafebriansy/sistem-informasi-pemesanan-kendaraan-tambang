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
        Employee::create([
            'username' => 'raka',
            'password' => Hash::make('password'),
            'name' => 'Raka Febrian',
            'admin_id' => $admin->id,
            'position' => 'staf',
        ]);
        Employee::create([
            'username' => 'ethan',
            'password' => Hash::make('password'),
            'name' => 'Ethan Hunt',
            'admin_id' => $admin->id,
            'position' => 'manajer',
        ]);
        Employee::create([
            'username' => 'joseph',
            'password' => Hash::make('password'),
            'name' => 'Joseph Goebbels',
            'admin_id' => $admin->id,
            'position' => 'kepala',
        ]);
    }
}
