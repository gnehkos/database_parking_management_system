<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParkingSlot extends Model
{
    protected $table = 'parking_slots';
    protected $primaryKey = 'slot_id';
    public $timestamps = false;

    protected $fillable = ['zone_id', 'slot_number', 'status', 'updated_at'];

    public function zone()
    {
        return $this->belongsTo(ParkingZone::class, 'zone_id');
    }

    public function activeTicket()
    {
        return $this->hasOne(Ticket::class, 'slot_id')->where('status', 'active');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'slot_id');
    }
}
