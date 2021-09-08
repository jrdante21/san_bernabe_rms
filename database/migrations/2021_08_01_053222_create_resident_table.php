<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResidentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resident', function (Blueprint $table) {
            $table->id();
            $table->string('fname', 50);
            $table->string('mname', 50);
            $table->string('lname', 50);
            $table->string('image', 100);
            $table->set('gender', ['male', 'female']);
            $table->set('civil', ['single', 'married', 'lived in', 'widowed']);
            $table->date('bday');
            $table->string('home_num', 10);
            $table->string('purok', 20);
            $table->string('brgy', 100);
            $table->string('muni', 100);
            $table->string('prov', 100);
            $table->integer('years_res');
            $table->string('father', 200);
            $table->string('mother', 200);
            $table->string('spouse', 200);
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
        Schema::dropIfExists('resident');
    }
}
