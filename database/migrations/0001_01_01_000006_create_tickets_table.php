<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->string('ticket_id', 30)->primary();
            $table->unsignedBigInteger('vehicle_id');
            $table->unsignedBigInteger('slot_id');
            $table->unsignedBigInteger('staff_id');
            $table->unsignedBigInteger('rate_id');
            $table->dateTime('entry_time');
            $table->dateTime('exit_time')->nullable();
            $table->string('barcode', 100)->unique();
            $table->enum('status', ['active', 'completed', 'cancelled'])->default('active');
            $table->dateTime('created_at');

            $table->foreign('vehicle_id')->references('vehicle_id')->on('vehicles');
            $table->foreign('slot_id')->references('slot_id')->on('parking_slots');
            $table->foreign('staff_id')->references('staff_id')->on('staff');
            $table->foreign('rate_id')->references('rate_id')->on('fee_rates');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
