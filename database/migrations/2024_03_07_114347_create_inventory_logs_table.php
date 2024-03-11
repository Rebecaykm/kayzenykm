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
        Schema::create('inventory_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('part_number_id')->nullable()->constrained('part_numbers');
            $table->double('quantity');
            $table->dateTime('capture_date');
            $table->string('order_number');
            $table->string('pack_number');
            $table->foreignId('cycle_inventory_id')->nullable()->constrained('cycle_inventories');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_logs');
    }
};
