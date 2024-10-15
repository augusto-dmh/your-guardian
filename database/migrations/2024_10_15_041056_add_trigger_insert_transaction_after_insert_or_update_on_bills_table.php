<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    // A transaction of type 'expense' is created based on the bill that gets paid.

    public function up(): void
    {
        DB::unprepared('
        CREATE TRIGGER insert_transaction_after_insert_on_bills
        AFTER INSERT on bills
        FOR EACH ROW
        BEGIN
            if NEW.status = "paid" THEN
                INSERT INTO transactions (user_id, bill_id, amount, type, title, description, created_at)
                VALUES (NEW.user_id, NEW.id, NEW.amount, "expense", NEW.title, NEW.description, NOW());
            END IF;
        END;
    ');

        DB::unprepared('
        CREATE TRIGGER insert_transaction_after_update_on_bills
        AFTER UPDATE on bills
        FOR EACH ROW
        BEGIN
            if NEW.status = "paid" AND OLD.status != "paid" THEN
                INSERT INTO transactions (user_id, bill_id, amount, type, title, description, created_at)
                VALUES (NEW.user_id, NEW.id, NEW.amount, "expense", NEW.title, NEW.description, NOW());
            END IF;
        END;
    ');
    }

    public function down(): void
    {
        DB::unprepared(
            'DROP TRIGGER IF EXISTS insert_transaction_after_insert_on_bills'
        );
        DB::unprepared(
            'DROP TRIGGER IF EXISTS insert_transaction_after_update_on_bills'
        );
    }
};
