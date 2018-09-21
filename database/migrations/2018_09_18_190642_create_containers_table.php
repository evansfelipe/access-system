<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Container;

class CreateContainersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('containers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('series_number', Container::LENGTHS['series_number']['max'])->required();
            $table->string('format', Container::LENGTHS['format']['max'])->nullable();
            $table->string('brand', Container::LENGTHS['brand']['max'])->nullable();
            $table->string('model', Container::LENGTHS['model']['max'])->nullable();
            $table->string('size', Container::LENGTHS['size']['max'])->nullable();
            $table->string('colour', Container::LENGTHS['colour']['max'])->nullable();
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
        Schema::dropIfExists('containers');
    }
}
