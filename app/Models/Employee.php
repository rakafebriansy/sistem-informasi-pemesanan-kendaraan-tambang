<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Notifications\Notifiable;

class Employee extends Authenticatable
{
    use Notifiable, HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        'admin_id',
        'name',
        'email',
        'password',
        'position',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
