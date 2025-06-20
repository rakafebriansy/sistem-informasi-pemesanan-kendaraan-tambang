<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use App\Models\VehicleType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = collect(['angkutan_barang', 'angkutan_orang']);

        for ($i = 0; $i < 20; $i++) {
            $type = VehicleType::inRandomOrder()->first();
            Vehicle::create([
                'vehicle_type_id' => $type->id,
                'code' => 'VHL-' . $i,
                'type' => $types->random(),
                'is_rent' => collect([true, false])->random(),
                'image' => 'images/default_vehicle.webp'
            ]);
        }
    }
}
