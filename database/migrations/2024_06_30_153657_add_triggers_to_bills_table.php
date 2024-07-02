<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddTriggersToBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
            CREATE TRIGGER set_paid_at_before_insert
            BEFORE INSERT ON bills
            FOR EACH ROW
            BEGIN
                IF NEW.status = "paid" THEN
                    SET NEW.paid_at = NOW();
                END IF;
            END;
        ');

        DB::unprepared('
            CREATE TRIGGER set_paid_at_before_update
            BEFORE UPDATE ON bills
            FOR EACH ROW
            BEGIN
                IF NEW.status = "paid" AND OLD.status <> "paid" THEN
                    SET NEW.paid_at = NOW();
                ELSEIF NEW.status <> "paid" THEN
                    SIGNAL SQLSTATE "45000" SET MESSAGE_TEXT = "Cannot directly update paid_at unless status changes to paid";
                END IF;
            END;
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER IF EXISTS set_paid_at_before_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS set_paid_at_before_update');
    }
}
