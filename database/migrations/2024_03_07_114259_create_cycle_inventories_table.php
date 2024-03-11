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
        Schema::create('cycle_inventories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_made_id');
            $table->unsignedBigInteger('user_validating_id');
            $table->unsignedBigInteger('status_id');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->timestamps();

            $table->foreign('user_made_id')->references('id')->on('users');
            $table->foreign('user_validating_id')->references('id')->on('users');
            $table->foreign('status_id')->references('id')->on('statuses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cycle_inventories');
    }
};
