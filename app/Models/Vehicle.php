<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $table = 'vehicles';
    protected $primaryKey = 'vehicle_id';

    protected $fillable = [
        'plate_number', 'vehicle_type', 'plate_type', 'status',
    ];

    const CREATED_AT = 'registered_at';
    const UPDATED_AT = 'updated_at';

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'vehicle_id');
    }

    public function activeTicket()
    {
        return $this->hasOne(Ticket::class, 'vehicle_id')->where('status', 'active');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
