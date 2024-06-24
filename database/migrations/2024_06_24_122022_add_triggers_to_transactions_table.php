<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddTriggersToTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     */
    public function up()
    {
        DB::unprepared('
            CREATE TRIGGER before_transactions_insert
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
            CREATE TRIGGER before_transactions_update
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

    /**
     * Reverse the migrations.
     *
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER IF EXISTS before_transactions_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS before_transactions_update');
    }
}
