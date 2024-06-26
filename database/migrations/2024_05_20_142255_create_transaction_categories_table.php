<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transaction_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table
                ->enum('transaction_type', ['expense', 'income'])
                ->default('expense');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transaction_categories');
    }
};
