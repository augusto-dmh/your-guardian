<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared('
            CREATE TRIGGER after_transaction_categories_update_in_type
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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared(
            'DROP TRIGGER IF EXISTS after_transaction_categories_update_in_type'
        );
    }
};
