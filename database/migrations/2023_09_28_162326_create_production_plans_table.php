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
        Schema::create('production_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('part_number_id')->nullable()->constrained('part_numbers');
            $table->double('plan_quantity')->nullable();
            $table->double('production_quantity')->nullable()->default(0);
            $table->date('date')->nullable();
            $table->foreignId('shift_id')->nullable()->constrained('shifts');
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_plans');
    }
};
