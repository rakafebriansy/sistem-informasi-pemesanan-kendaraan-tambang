<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = ['Surabaya', 'Jakarta', 'Bandung', 'Semarang', 'Malang', 'Jember', 'Jepara', 'Manokwari', 'Bintan'];
        foreach ($cities as $city) {
            Region::create([
                'name' => $city
            ]);
        }
    }
}
