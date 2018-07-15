<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Company;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', Company::LENGTHS['name']['max'])->required();
            $table->string('area', Company::LENGTHS['area']['max'])->required();
            $table->string('cuit', Company::LENGTHS['cuit']['max'])->unique()->required();
            $table->integer('residency_id')->unsigned()->required();
            $table->datetime('expiration')->required();
            $table->json('contact')->required();
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
        Schema::dropIfExists('companies');
    }
}
