<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    // 'paid_at' becomes null when the status of the bill is changed from 'paid' to another one.

    public function up(): void
    {
        DB::unprepared('
            CREATE TRIGGER set_paid_at_null_before_update_on_bills
            BEFORE UPDATE ON bills
            FOR EACH ROW
            BEGIN
                if OLD.status = "paid" AND NEW.status <> "paid" THEN
                SET NEW.paid_at = NULL;
                END IF;
            END
        ');
    }

    public function down(): void
    {
        DB::unprepared(
            'DROP TRIGGER IF EXISTS set_paid_at_null_before_update_on_bills'
        );
    }
};
