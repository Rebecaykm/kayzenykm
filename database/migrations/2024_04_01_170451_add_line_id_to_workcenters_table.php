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
        Schema::table('workcenters', function (Blueprint $table) {
            $table->foreignId('line_id')->nullable()->constrained('lines');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workcenters', function (Blueprint $table) {
            $table->dropForeign(['line_id']);
            $table->dropColumn('line_id');
        });
    }
};
