<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableResidentChildren extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resident_children', function (Blueprint $table) {
            $table->unsignedBigInteger('resident_id');
            $table->foreign('resident_id')->references('id')->on('resident');
            $table->string('name', 500);
            $table->date('bday');
            $table->set('gender', ['male', 'female']);
            $table->set('civil', ['single', 'married', 'lived in', 'widowed']);
            $table->boolean('has_school');
            $table->set('school_level', ['primary', 'secondary', 'tertiary', 'vocational']);
            $table->string('school_name', 500);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('resident_children');
    }
}
