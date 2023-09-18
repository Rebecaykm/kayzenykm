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
            $table->foreignId('measurement_id')->nullable()->constrained('measurements');
            $table->foreignId('type_id')->nullable()->constrained('types');
            $table->foreignId('item_class_id')->nullable()->constrained('item_classes');
            $table->foreignId('standard_package_id')->nullable()->constrained('standard_packages');
            $table->foreignId('workcenter_id')->nullable()->constrained('workcenters');
            $table->foreignId('planner_id')->nullable()->constrained('planners');
            $table->foreignId('project_id')->nullable()->constrained('projects');
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
