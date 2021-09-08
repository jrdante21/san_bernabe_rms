<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableResidentAssets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resident_assets', function (Blueprint $table) {
            $table->unsignedBigInteger('resident_id');
            $table->foreign('resident_id')->references('id')->on('resident');
            $table->string('type', 50);
            $table->json('items');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('resident_assets');
    }
}
