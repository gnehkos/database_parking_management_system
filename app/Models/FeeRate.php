<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeeRate extends Model
{
    protected $table = 'fee_rates';
    protected $primaryKey = 'rate_id';
    public $timestamps = false;

    protected $fillable = ['vehicle_type', 'short_stay_fee', 'long_stay_fee', 'threshold_hours', 'updated_at'];

    const KHR_RATE = 4000;

    public function calculateFee(float $durationHours): float
    {
        if ($durationHours < $this->threshold_hours) {
            return $this->short_stay_fee;
        }
        return $this->long_stay_fee;
    }
}
