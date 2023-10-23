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
        Schema::create('scrap_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_plan_id')->nullable()->constrained('production_plans');
            $table->foreignId('part_number_id')->constrained('part_numbers');
            $table->foreignId('scrap_id')->constrained('scraps');
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->double('quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scrap_records');
    }
};
