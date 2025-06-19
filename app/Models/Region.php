<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasUuids;
    
    protected $fillable = [
        'name',
    ];

    public function vehicles() 
    {
        return $this->hasMany(Vehicle::class);
    }
}
