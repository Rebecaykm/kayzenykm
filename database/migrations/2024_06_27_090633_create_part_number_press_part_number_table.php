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
        Schema::create('part_number_press_part_number', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('part_number_id');
            $table->unsignedBigInteger('press_part_number_id');

            $table->foreign('part_number_id')->references('id')->on('part_numbers');
            $table->foreign('press_part_number_id')->references('id')->on('press_part_numbers');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('part_number_press_part_number');
    }
};
