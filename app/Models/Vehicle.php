<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasUuids;

    protected $fillable = [
        'vehicle_type_id',
        'code',
        'type',
        'is_rent',
    ];

    public function usageHistories()
    {
        return $this->hasMany(UsageHistory::class);
    }
    public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class);
    }
}