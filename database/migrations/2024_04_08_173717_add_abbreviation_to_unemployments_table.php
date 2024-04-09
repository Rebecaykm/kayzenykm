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
        Schema::table('unemployments', function (Blueprint $table) {
            $table->string('abbreviation')->nullable();
            $table->foreignId('unemployment_type_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('unemployments', function (Blueprint $table) {
            $table->dropColumn('abbreviation');
        });
    }
};
