<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntrancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entrances', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->required();
            $table->integer('card_number')->unsigned()->required();
            $table->text('description')->required();
            $table->text('vehicle_description')->nullable();
            $table->text('container_description')->nullable();
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
        Schema::dropIfExists('entrances');
    }
}
