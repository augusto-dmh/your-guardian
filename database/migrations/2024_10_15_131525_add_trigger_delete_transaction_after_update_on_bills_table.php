<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared('
            CREATE TRIGGER delete_transaction_after_update_on_bills
            AFTER UPDATE ON bills
            FOR EACH ROW
            BEGIN
                IF OLD.status = "paid" AND NEW.status <> "paid" THEN
                    DELETE FROM transactions
                    WHERE bill_id = OLD.id;
                END IF;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared(
            'DROP TRIGGER IF EXISTS delete_transaction_after_update_on_bills'
        );
    }
};
