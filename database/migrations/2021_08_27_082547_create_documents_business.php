<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentsBusiness extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents_business', function (Blueprint $table) {
            $table->unsignedBigInteger('documents_id');
            $table->foreign('documents_id')->references('id')->on('documents');
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
        Schema::dropIfExists('documents_business');
    }
}
