<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableResidentSchool extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resident_school', function (Blueprint $table) {
            $table->unsignedBigInteger('resident_id');
            $table->foreign('resident_id')->references('id')->on('resident');
            $table->integer('level');
            $table->string('name', 200);
            $table->string('address', 500);
            $table->date('year');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('resident_school');
    }
}
