<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParkingZone extends Model
{
    protected $table = 'parking_zones';
    protected $primaryKey = 'zone_id';
    public $timestamps = false;

    public function slots()
    {
        return $this->hasMany(ParkingSlot::class, 'zone_id');
    }
}
