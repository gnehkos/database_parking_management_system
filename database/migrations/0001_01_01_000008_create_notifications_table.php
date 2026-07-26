<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id('notification_id');
            $table->unsignedBigInteger('staff_id');
            $table->string('title', 100);
            $table->text('message');
            $table->enum('type', ['check_in', 'check_out', 'system', 'alert']);
            $table->tinyInteger('is_read')->default(0);
            $table->dateTime('created_at');

            $table->foreign('staff_id')->references('staff_id')->on('staff');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
