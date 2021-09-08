<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableResidentAssets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('resident_assets', function(Blueprint $table){
            $table->dropColumn('items');
            $table->string('v_brand', 50)->nullable();
            $table->string('v_model', 50)->nullable();
            $table->string('v_plate', 50)->nullable();
            $table->string('v_color', 50)->nullable();
            $table->string('a_type', 50)->nullable();
            $table->set('a_sex', ['male', 'female'])->nullable();
            $table->string('a_cert', 50)->nullable();
            $table->string('l_type', 100)->nullable();
            $table->string('l_hectar', 50)->nullable();
            $table->string('l_loc', 200)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
