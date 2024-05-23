<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('wallet_id');
            $table->unsignedBigInteger('transaction_category_id');
            $table->decimal('amount', 10, 2);
            $table->enum('type', ['income', 'expense']);
            $table->timestamps();

            $table->foreign('wallet_id', 'fk_transactions_wallet_id')->references('id')->on('wallets')->onDelete('cascade');
            $table->foreign('transaction_category_id', 'fk_transactions_transaction_category_id')->references('id')->on('transaction_categories')->onDelete('set null');
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
