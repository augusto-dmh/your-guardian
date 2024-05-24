<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('task_category_id')->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('due_date');
            $table
                ->enum('status', ['pending', 'completed', 'failed'])
                ->default('pending');
            $table->timestamps();

            $table
                ->foreign('user_id', 'fk_tasks_user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table
                ->foreign('task_category_id', 'fk_tasks_task_category_id')
                ->references('id')
                ->on('task_categories')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
