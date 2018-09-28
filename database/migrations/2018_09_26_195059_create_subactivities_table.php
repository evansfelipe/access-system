<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Subactivity;
class CreateSubactivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subactivities', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('activity_id')->unsigned()->required();
            $table->string('name', Subactivity::LENGTHS['name']['max'])->required();
            $table->timestamps();
            $table->unique(['activity_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subactivities');
    }
}
