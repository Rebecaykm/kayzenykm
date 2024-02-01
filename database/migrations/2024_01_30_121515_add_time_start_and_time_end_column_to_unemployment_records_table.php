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
            $table->dateTime('time_start')->nullable()->after('unemployment_id');
            $table->dateTime('time_end')->nullable()->after('time_start');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('unemployment_records', function (Blueprint $table) {
            $table->dropColumn(['time_start', 'time_end']);
        });
    }
};
