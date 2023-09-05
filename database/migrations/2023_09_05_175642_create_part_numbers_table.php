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
        Schema::create('part_numbers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('number');
            $table->foreignId('measurement_id')->constrained('measurements');
            $table->foreignId('type_id')->constrained('types');
            $table->foreignId('item_class_id')->constrained('item_classes');
            $table->foreignId('standar_package_id')->constrained('standard_packages');
            $table->foreignId('workcenter_id')->constrained('workcenters');
            $table->foreignId('planner_id')->constrained('planners');
            $table->foreignId('project_id')->constrained('projects');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('part_numbers');
    }
};
