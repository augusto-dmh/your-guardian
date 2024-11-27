<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notification_channel_user', function (Blueprint $table) {
            $table->unsignedTinyInteger('notification_channel_id');
            $table->unsignedBigInteger('user_id');

            $table->primary(['notification_channel_id', 'user_id']);
            $table
                ->foreign('notification_channel_id')
                ->references('id')
                ->on('notification_channels')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_channel_user');
    }
};
