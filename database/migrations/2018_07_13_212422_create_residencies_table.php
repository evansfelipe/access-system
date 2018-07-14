<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResidenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('residencies', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('person_id')->unsigned()->required();
            $table->string('street')->nullable();
            $table->string('apartment',15)->nullable();
            $table->string('cp',10)->nullable();
            $table->string('country',25)->nullable();
            $table->string('province',25)->nullable();
            $table->string('city',25)->nullable();
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
        Schema::dropIfExists('residencies');
    }
}
