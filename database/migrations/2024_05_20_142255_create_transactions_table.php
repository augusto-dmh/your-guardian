<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('bill_id')->nullable();
            $table->unsignedBigInteger('transaction_category_id')->nullable();
            $table->decimal('amount', 11, 2);
            $table->enum('type', ['income', 'expense'])->default('expense');
            $table->text('description');
            $table->timestamps();

            $table
                ->foreign('user_id', 'fk_transactions_user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table
                ->foreign('bill_id', 'fk_transactions_bill_id')
                ->references('id')
                ->on('bills')
                ->onDelete('cascade');
            $table
                ->foreign(
                    'transaction_category_id',
                    'fk_transactions_transaction_category_id'
                )
                ->references('id')
                ->on('transaction_categories')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
