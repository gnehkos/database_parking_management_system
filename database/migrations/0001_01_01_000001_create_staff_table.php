<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staff', function (Blueprint $table) {
            $table->id('staff_id');
            $table->string('username', 50)->unique();
            $table->string('password_hash', 255);
            $table->string('full_name', 255);
            $table->enum('gender', ['male', 'female']);
            $table->enum('role', ['admin', 'staff']);
            $table->string('phone_number', 20);
            $table->enum('status', ['active', 'deactivated'])->default('active');
            $table->date('date_of_birth');
            $table->string('profile_image', 255)->nullable();
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
