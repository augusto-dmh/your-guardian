<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    // Restrict transaction insert when there's no match between the transaction type and the transaction category type.

    public function up(): void
    {
        DB::unprepared('
            CREATE TRIGGER restrict_transaction_before_insert_on_transactions
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

        DB::unprepared('
            CREATE TRIGGER restrict_transaction_before_update_on_transactions
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

    public function down(): void
    {
        DB::unprepared(
            'DROP TRIGGER IF EXISTS restrict_transaction_before_insert_on_transactions'
        );
        DB::unprepared(
            'DROP TRIGGER IF EXISTS restrict_transaction_before_update_on_transactions'
        );
    }
};
