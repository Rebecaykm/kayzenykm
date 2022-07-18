<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFsoLocalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fso_locals', function (Blueprint $table) {
            $table->id();
            $table->string('SID');
            $table->string('SWRKC');
            $table->string('SDDTE');
            $table->string('SORD');
            $table->string('SPROD');
            $table->string('SQREQ');
            $table->string('SQFIN');
            $table->string('CDTE');
            $table->integer('CANC');
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
        Schema::dropIfExists('fso_locals');
    }
}
