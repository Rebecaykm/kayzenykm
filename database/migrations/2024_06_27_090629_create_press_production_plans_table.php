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
        Schema::create('press_production_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('press_part_number_id')->constrained('press_part_numbers');
            $table->date('plan_date');
            $table->foreignId('shift_id')->constrained('shifts');
            $table->string('component_code');
            $table->integer('hits');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('press_production_plans');
    }
};
