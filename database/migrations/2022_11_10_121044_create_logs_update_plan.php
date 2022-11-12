<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logs_update_plan', function (Blueprint $table) {
            $table->id();
            $table->string( 'K6PROD' );
            $table->string('K6WRKC');
            $table->date('K6SDTE');
            $table->date('K6EDTE');
            $table->date('K6DDTE');
            $table->string('K6DSHT');
            $table->integer('K6PFQY');
            $table->string('K6CUSR');
            $table->date('K6CCDT');
            $table->time('K6CCTM');
            $table->string('K6FIL1');
            $table->string('K6FIL2');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('logs_update_plan');
    }
};
