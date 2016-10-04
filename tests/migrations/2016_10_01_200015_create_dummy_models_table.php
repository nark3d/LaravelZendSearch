<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateDummyModelsTable
 */
class CreateDummyModelsTable extends Migration
{
    /**
     * Up - great film, but it does make me cry...
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dummy_models', function (Blueprint $table) {
            $table->increments('id');
            $table->string('data');
            $table->timestamps();
        });
    }

    /**
     * Down dobee do down down
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('dummy_models');
    }
}
