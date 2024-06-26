<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('amount', 11, 2);
            $table->date('due_date');
            $table
                ->enum('status', ['pending', 'paid', 'overdue'])
                ->default('pending');
            $table->timestamps();
            $table->date('paid_at')->nullable();

            $table
                ->foreign('user_id', 'fk_bills_user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
