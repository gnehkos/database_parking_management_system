<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $table = 'tickets';
    protected $primaryKey = 'ticket_id';
    public $incrementing = false;
    protected $keyType = 'string';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    protected $fillable = [
        'ticket_id', 'vehicle_id', 'slot_id', 'staff_id', 'rate_id',
        'entry_time', 'exit_time', 'barcode', 'status', 'created_at',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    public function slot()
    {
        return $this->belongsTo(ParkingSlot::class, 'slot_id');
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    public function feeRate()
    {
        return $this->belongsTo(FeeRate::class, 'rate_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'ticket_id', 'ticket_id');
    }
}
