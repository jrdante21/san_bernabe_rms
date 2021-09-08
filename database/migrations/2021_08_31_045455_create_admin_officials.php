<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminOfficials extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_officials', function (Blueprint $table) {
            $table->id();
            $table->string('fname', 50);
            $table->string('mname', 50);
            $table->string('lname', 50);
            $table->string('title', 20);
            $table->string('position', 50);
            $table->string('username', 100);
            $table->string('password', 100);
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
        Schema::dropIfExists('admin_officials');
    }
}
