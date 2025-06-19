<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class UsageHistory extends Model
{
    use HasUuids;

    protected $fillable = [
        'vehicle_id',
        'renter_id',
        'driver_id',
        'region_id',
        'start_date',
        'end_date',
        'fuel_consumption',
        'status',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
    public function region()
    {
        return $this->belongsTo(Region::class);
    }
    public function driver()
    {
        return $this->belongsTo(Employee::class, 'driver_id','id');
    }
    public function renter()
    {
        return $this->belongsTo(Employee::class, 'renter_id','id');
    }
}
