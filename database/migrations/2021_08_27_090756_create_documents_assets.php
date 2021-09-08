<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentsAssets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents_assets', function (Blueprint $table) {
            $table->unsignedBigInteger('documents_id');
            $table->foreign('documents_id')->references('id')->on('documents');
            $table->string('purpose', 500);
            $table->json('assets');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('documents_assets');
    }
}
