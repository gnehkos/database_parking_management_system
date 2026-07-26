<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id('vehicle_id');
            $table->string('plate_number', 20)->nullable();
            $table->enum('vehicle_type', ['bike', 'car', 'motorcycle', 'tricycle']);
            $table->enum('plate_type', ['structured', 'custom'])->default('structured');
            $table->enum('status', ['active', 'deleted'])->default('active');
            $table->dateTime('registered_at');
            $table->dateTime('updated_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
