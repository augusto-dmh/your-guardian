<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    // 'paid_at' is updated with the current timestamp when the bill's status is updated to 'paid'.

    public function up(): void
    {
        DB::unprepared('
            CREATE TRIGGER set_paid_at_before_insert_on_bills
            BEFORE INSERT ON bills
            FOR EACH ROW
            BEGIN
                IF NEW.status = "paid" THEN
                    SET NEW.paid_at = NOW();
                END IF;
            END;
        ');

        DB::unprepared('
            CREATE TRIGGER set_paid_at_before_update_on_bills
            BEFORE UPDATE ON bills
            FOR EACH ROW
            BEGIN
                IF NEW.status = "paid" AND OLD.status <> "paid" THEN
                    SET NEW.paid_at = NOW();
                END IF;
            END;
        ');
    }

    public function down(): void
    {
        DB::unprepared(
            'DROP TRIGGER IF EXISTS set_paid_at_before_insert_on_bills'
        );
        DB::unprepared(
            'DROP TRIGGER IF EXISTS set_paid_at_before_update_on_bills'
        );
    }
};
