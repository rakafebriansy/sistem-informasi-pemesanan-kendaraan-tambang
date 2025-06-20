<?php

namespace Database\Seeders;

use App\Models\Employee;
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
        $this->call(AdminSeeder::class);
        $this->call(EmployeeSeeder::class);
        $this->call([RegionSeeder::class, VehicleTypeSeeder::class]);
        $this->call(VehicleSeeder::class);
        $this->call(UsageHistorySeeder::class);
    }
}
