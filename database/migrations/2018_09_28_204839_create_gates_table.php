<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Gate;
class CreateGatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', Gate::LENGTHS['name']['max'])->required()->unique();
            $table->boolean('enabled')->default(false);
            $table->integer('zone_id')->unsigned()->required();
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
        Schema::dropIfExists('gates');
    }
}
