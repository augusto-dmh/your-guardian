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
                END IF;
            END;
        ');

        DB::unprepared('
            CREATE TRIGGER restrict_paid_at_update
            BEFORE UPDATE ON bills
            FOR EACH ROW
            BEGIN
                IF NEW.status != "paid" AND NEW.paid_at <> OLD.paid_at THEN
                    SIGNAL SQLSTATE "45000" SET MESSAGE_TEXT = "Cannot directly update paid_at unless status changes to or is already paid";
                END IF;

                IF NEW.paid_at > NOW() THEN
                    SIGNAL SQLSTATE "45000" SET MESSAGE_TEXT = "Cannot set paid_at with a date that hasn\'t come yet.";
                END IF;
            END;
        ');

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

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER IF EXISTS set_paid_at_before_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS set_paid_at_before_update');
        DB::unprepared(
            'DROP TRIGGER IF EXISTS insert_transaction_after_insert_on_bills'
        );
        DB::unprepared(
            'DROP TRIGGER IF EXISTS insert_transaction_after_update_on_bills'
        );
    }
}
