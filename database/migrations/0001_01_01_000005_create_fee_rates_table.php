<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fee_rates', function (Blueprint $table) {
            $table->id('rate_id');
            $table->enum('vehicle_type', ['bike', 'car', 'motorcycle', 'tricycle'])->unique();
            $table->decimal('short_stay_fee', 10, 2);
            $table->decimal('long_stay_fee', 10, 2);
            $table->integer('threshold_hours')->default(5);
            $table->dateTime('updated_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fee_rates');
    }
};
