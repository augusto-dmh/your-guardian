<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    // Update on 'paid_at' without the status being or going to be 'paid' is restricted.

    public function up(): void
    {
        DB::unprepared('
            CREATE TRIGGER restrict_paid_at_before_update_on_bills
            BEFORE UPDATE ON bills
            FOR EACH ROW
            BEGIN
                IF NEW.status != "paid" AND NEW.paid_at <> OLD.paid_at THEN
                    SIGNAL SQLSTATE "45000" SET MESSAGE_TEXT = "Cannot directly update paid_at unless status changes to or is already paid";
                END IF;
            END;
        ');
    }

    public function down(): void
    {
        DB::unprepared(
            'DROP TRIGGER IF EXISTS restrict_paid_at_before_update_on_bills'
        );
    }
};
