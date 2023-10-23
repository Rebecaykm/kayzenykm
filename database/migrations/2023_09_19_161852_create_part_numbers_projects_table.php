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
        Schema::create('part_numbers_projects', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('part_number_id');
            $table->unsignedBigInteger('project_id');

            $table->foreign('part_number_id')->references('id')->on('part_numbers');
            $table->foreign('project_id')->references('id')->on('projects');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('part_numbers_projects');
    }
};
