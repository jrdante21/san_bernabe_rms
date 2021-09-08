<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableResidentBusiness extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resident_business', function (Blueprint $table) {
            $table->unsignedBigInteger('resident_id');
            $table->foreign('resident_id')->references('id')->on('resident');
            $table->string('name', 500);
            $table->string('address', 500);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('resident_business');
    }
}
