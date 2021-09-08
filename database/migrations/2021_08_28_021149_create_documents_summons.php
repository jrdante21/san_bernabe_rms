<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentsSummons extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents_summons', function (Blueprint $table) {
            $table->unsignedBigInteger('documents_id');
            $table->foreign('documents_id')->references('id')->on('documents');
            $table->string('respondent', 500);
            $table->string('reason', 1000);
            $table->datetime('schedule');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('documents_summons');
    }
}
