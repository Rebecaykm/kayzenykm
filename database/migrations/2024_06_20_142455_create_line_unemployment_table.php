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
        Schema::create('line_unemployment', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('line_id');
            $table->unsignedBigInteger('unemployment_id');
            $table->foreign('line_id')->references('id')->on('lines')->onDelete('cascade');
            $table->foreign('unemployment_id')->references('id')->on('unemployments')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('line_unemployment');
    }
};
