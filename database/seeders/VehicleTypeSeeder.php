<?php

namespace Database\Seeders;

use App\Models\VehicleType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VehicleTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vehicleTypes = ['Truk', 'Sepeda Motor', 'Mobil', 'Dump Truck', 'Excavator', 'Dozer', 'Water Tank', 'Light Vehicle'];
        foreach ($vehicleTypes as $vehicleType) {
            VehicleType::create([
                'name' => $vehicleType
            ]);
        }
    }
}
