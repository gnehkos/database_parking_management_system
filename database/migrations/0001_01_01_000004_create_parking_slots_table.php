<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parking_slots', function (Blueprint $table) {
            $table->id('slot_id');
            $table->unsignedBigInteger('zone_id');
            $table->string('slot_number', 10)->unique();
            $table->enum('status', ['available', 'occupied', 'maintenance'])->default('available');
            $table->dateTime('updated_at');

            $table->foreign('zone_id')->references('zone_id')->on('parking_zones');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parking_slots');
    }
};
