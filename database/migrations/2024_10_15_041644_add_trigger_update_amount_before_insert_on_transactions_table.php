<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    // The amount based on the type of transaction is adjusted before inserting/updating it
    // ex: 1500 of expense? then it is stored in 'amount' field -1500.

    public function up(): void
    {
        DB::unprepared('
            CREATE TRIGGER adjust_amount_before_insert_on_transactions
            BEFORE INSERT ON transactions
            FOR EACH ROW
            BEGIN
                IF NEW.type = "expense" THEN
                    SET NEW.amount = -ABS(NEW.amount);
                ELSEIF NEW.type = "income" THEN
                    SET NEW.amount = ABS(NEW.amount);
                END IF;
            END;
        ');

        DB::unprepared('
            CREATE TRIGGER adjust_amount_before_update_on_transactions
            BEFORE UPDATE ON transactions
            FOR EACH ROW
            BEGIN
                IF NEW.type = "expense" THEN
                    SET NEW.amount = -ABS(NEW.amount);
                ELSEIF NEW.type = "income" THEN
                    SET NEW.amount = ABS(NEW.amount);
                END IF;
            END;
        ');
    }

    public function down(): void
    {
        DB::unprepared(
            'DROP TRIGGER IF EXISTS adjust_amount_before_insert_on_transactions'
        );
        DB::unprepared(
            'DROP TRIGGER IF EXISTS adjust_amount_before_update_on_transactions'
        );
    }
};
