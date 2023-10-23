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
        Schema::create('prodcution_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('part_number_id')->constrained('part_numbers');
            $table->double('quantity');
            $table->string('sequence');
            $table->time('time_start');
            $table->time('time_end');
            $table->string('minutes');
            $table->foreignId('status_id')->nullable()->constrained('statuses');
            $table->foreignId('production_plan_id')->constrained('production_plans');
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prodcution_records');
    }
};
