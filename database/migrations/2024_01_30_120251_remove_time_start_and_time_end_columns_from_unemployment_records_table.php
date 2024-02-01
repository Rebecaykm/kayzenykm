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
        Schema::table('unemployment_records', function (Blueprint $table) {
            $table->dropColumn(['time_start', 'time_end']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('unemployment_records', function (Blueprint $table) {
            $table->time('time_start');
            $table->time('time_end');
        });
    }
};
