<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Region;
use App\Models\UsageHistory;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsageHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statusses = collect(['not_accepted_yet', 'accepted_by_manager', 'accepted_by_chief', 'done', 'canceled']);
        $start = Carbon::parse('2025-05-15 00:00:00');
        $end = Carbon::parse('2025-06-15 00:00:00');

        for ($i = 0; $i < 60; $i++) {
            $vehicle = Vehicle::inRandomOrder()->first();
            $renter = Employee::inRandomOrder()->first();
            $driver = Employee::inRandomOrder()->first();
            $region = Region::inRandomOrder()->first();

            $randomStartDate = Carbon::createFromTimestamp(rand($start->timestamp, $end->timestamp));
            $daysToAdd = rand(1, 30);
            $randomEndDate = $randomStartDate->copy()->addDays($daysToAdd);

            UsageHistory::create([
                'vehicle_id' => $vehicle->id,
                'renter_id' => $renter->id,
                'driver_id' => $driver->id,
                'region_id' => $region->id,
                'start_date' => $randomStartDate,
                'end_date' => $randomEndDate,
                'fuel_consumption' => rand(1, 500),
                'status' => $statusses->random(),
            ]);
        }
    }
}
