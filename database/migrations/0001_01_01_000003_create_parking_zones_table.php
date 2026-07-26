<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parking_zones', function (Blueprint $table) {
            $table->id('zone_id');
            $table->string('zone_name', 50);
            $table->string('zone_code', 5)->unique();
            $table->enum('vehicle_type', ['bike', 'car', 'motorcycle', 'tricycle']);
            $table->string('slot_prefix', 5);
            $table->integer('start_number');
            $table->integer('end_number');
            $table->integer('total_slots');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parking_zones');
    }
};
