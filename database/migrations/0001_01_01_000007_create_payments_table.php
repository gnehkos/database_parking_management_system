<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id('payment_id');
            $table->string('ticket_id', 30);
            $table->unsignedBigInteger('staff_id');
            $table->decimal('duration', 6, 2);
            $table->decimal('total_fee', 10, 2);
            $table->enum('payment_method', ['cash', 'card', 'qrScan']);
            $table->dateTime('paid_at');

            $table->foreign('ticket_id')->references('ticket_id')->on('tickets');
            $table->foreign('staff_id')->references('staff_id')->on('staff');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
