<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBorrowed extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('borrowed', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('resident_id');
            $table->foreign('resident_id')->references('id')->on('resident');
            $table->string('material', 100);
            $table->set('status', ['good', 'damaged', 'lost']);
            $table->double('amount')->default(0);
            $table->double('quantity')->default(0);
            $table->string('or_num', 50);
            $table->date('paid_at');
            $table->date('returned_at');
            $table->date('issued_at');
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
        Schema::dropIfExists('borrowed');
    }
}
