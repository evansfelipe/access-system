<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Vehicle;

class CreateVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->unsigned()->required();
            $table->integer('type_id')->required();
            $table->string('owner', Vehicle::LENGTHS['owner']['max'])->required();
            $table->string('plate', Vehicle::LENGTHS['plate']['max'])->unique()->required();
            $table->string('brand', Vehicle::LENGTHS['brand']['max'])->required();
            $table->string('model', Vehicle::LENGTHS['model']['max'])->required();
            $table->string('colour', Vehicle::LENGTHS['colour']['max'])->nullable();
            $table->year('year')->nullable();
            $table->datetime('insurance')->nullable();
            $table->datetime('vtv')->nullable();
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
        Schema::dropIfExists('vehicles');
    }
}
