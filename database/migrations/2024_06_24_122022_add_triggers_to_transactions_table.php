<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddTriggersToTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     */
    public function up()
    {
        // Adjust the amount based on the type of transaction before insert
        DB::unprepared('
            CREATE TRIGGER before_transactions_insert_adjust_amount
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

        // Adjust the amount based on the type of transaction before update
        DB::unprepared('
            CREATE TRIGGER before_transactions_update_adjust_amount
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

        // Check if the transaction type matches the category type before insert
        DB::unprepared('
            CREATE TRIGGER before_transactions_insert_check_type
            BEFORE INSERT ON transactions
            FOR EACH ROW
            BEGIN
                DECLARE category_type ENUM("income", "expense") CHARSET utf8mb4 COLLATE utf8mb4_unicode_ci;

                -- Get the type of the transaction category
                SELECT transaction_type INTO category_type
                FROM transaction_categories
                WHERE id = NEW.transaction_category_id;

                -- Ensure the transaction type matches the category type
                IF category_type COLLATE utf8mb4_unicode_ci != NEW.type COLLATE utf8mb4_unicode_ci THEN
                    SIGNAL SQLSTATE "45000"
                    SET MESSAGE_TEXT = "Transaction type and transaction category type must match";
                END IF;
            END;
        ');

        // Check if the transaction type matches the category type before update
        DB::unprepared('
            CREATE TRIGGER before_transactions_update_check_type
            BEFORE UPDATE ON transactions
            FOR EACH ROW
            BEGIN
                DECLARE category_type ENUM("income", "expense") CHARSET utf8mb4 COLLATE utf8mb4_unicode_ci;

                -- Get the type of the transaction category
                SELECT transaction_type INTO category_type
                FROM transaction_categories
                WHERE id = NEW.transaction_category_id;

                -- Ensure the transaction type matches the category type
                IF category_type COLLATE utf8mb4_unicode_ci != NEW.type COLLATE utf8mb4_unicode_ci THEN
                    SIGNAL SQLSTATE "45000"
                    SET MESSAGE_TEXT = "Transaction type and transaction category type must match";
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
        DB::unprepared(
            'DROP TRIGGER IF EXISTS before_transactions_insert_adjust_amount'
        );
        DB::unprepared(
            'DROP TRIGGER IF EXISTS before_transactions_update_adjust_amount'
        );
        DB::unprepared(
            'DROP TRIGGER IF EXISTS before_transactions_insert_check_type'
        );
        DB::unprepared(
            'DROP TRIGGER IF EXISTS before_transactions_update_check_type'
        );
    }
}
