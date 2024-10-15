<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    //  Transactions associated with a transaction category whose 'transaction_type' is changed have their category set to null.

    public function up(): void
    {
        DB::unprepared('
            CREATE TRIGGER set_transaction_type_null_after_update_on_transaction_categories
            AFTER UPDATE ON transaction_categories
            FOR EACH ROW
            BEGIN
                IF NEW.transaction_type != OLD.transaction_type THEN
                    UPDATE transactions
                    SET transaction_category_id = NULL
                    WHERE transaction_category_id = NEW.id;
                END IF;
            END;
        ');
    }

    public function down(): void
    {
        DB::unprepared(
            'DROP TRIGGER IF EXISTS set_transaction_type_null_after_update_on_transaction_categories'
        );
    }
};
