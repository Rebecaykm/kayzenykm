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
        Schema::create('label_prints', function (Blueprint $table) {
            $table->id();
            $table->integer('print_count')->nullable();
            $table->longText('print_reason')->nullable();
            $table->foreignId('prodcution_record_id')->constrained('prodcution_records');
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('label_prints');
    }
};
