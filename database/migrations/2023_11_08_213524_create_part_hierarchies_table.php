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
        Schema::create('part_hierarchies', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('main_part_number_id');
            $table->unsignedBigInteger('sub_part_number_id');
            $table->double('required_quantity');

            $table->foreign('main_part_number_id')->references('id')->on('part_numbers');
            $table->foreign('sub_part_number_id')->references('id')->on('part_numbers');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('part_hierarchies');
    }
};
