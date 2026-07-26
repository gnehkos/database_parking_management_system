<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
    {
        Schema::table('parking_slots', function (Blueprint $table) {
            $table->index('status', 'idx_slots_status');
            $table->foreign('zone_id', 'fk_slots_zone')
                ->references('zone_id')
                ->on('parking_zones')
                ->onDelete('cascade');
        });

        Schema::table('tickets', function (Blueprint $table) {
            $table->index('status', 'idx_tickets_status');
            $table->index('vehicle_id', 'idx_tickets_vehicle');
            $table->index('slot_id', 'idx_tickets_slot');
            $table->index('staff_id', 'idx_tickets_staff');
            $table->index('entry_time', 'idx_tickets_entry');
            $table->foreign('vehicle_id', 'fk_tickets_vehicle')
                ->references('vehicle_id')
                ->on('vehicles')
                ->onDelete('restrict');
            $table->foreign('slot_id', 'fk_tickets_slot')
                ->references('slot_id')
                ->on('parking_slots')
                ->onDelete('restrict');
            $table->foreign('staff_id', 'fk_tickets_staff')
                ->references('staff_id')
                ->on('staff')
                ->onDelete('restrict');
            $table->foreign('rate_id', 'fk_tickets_rate')
                ->references('rate_id')
                ->on('fee_rates')
                ->onDelete('restrict');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->index('paid_at', 'idx_payments_paid_at');
            $table->index('staff_id', 'idx_payments_staff');
            $table->foreign('ticket_id', 'fk_payments_ticket')
                ->references('ticket_id')
                ->on('tickets')
                ->onDelete('cascade');
            $table->foreign('staff_id', 'fk_payments_staff')
                ->references('staff_id')
                ->on('staff')
                ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign('fk_payments_ticket');
            $table->dropForeign('fk_payments_staff');
            $table->dropIndex('idx_payments_paid_at');
            $table->dropIndex('idx_payments_staff');
        });

        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign('fk_tickets_vehicle');
            $table->dropForeign('fk_tickets_slot');
            $table->dropForeign('fk_tickets_staff');
            $table->dropForeign('fk_tickets_rate');
            $table->dropIndex('idx_tickets_status');
            $table->dropIndex('idx_tickets_vehicle');
            $table->dropIndex('idx_tickets_slot');
            $table->dropIndex('idx_tickets_staff');
            $table->dropIndex('idx_tickets_entry');
        });

        Schema::table('parking_slots', function (Blueprint $table) {
            $table->dropForeign('fk_slots_zone');
            $table->dropIndex('idx_slots_status');
        });
    }
};
