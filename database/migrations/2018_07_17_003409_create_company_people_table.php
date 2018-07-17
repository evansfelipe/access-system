<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\CompanyPerson;

class CreateCompanyPeopleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_people', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('person_id')->unsigned()->required();
            $table->integer('company_id')->unsigned();
            $table->integer('activity_id')->unsigned()->required();
            $table->string('art',CompanyPerson::LENGTHS['art']['max'])->required();
            $table->datetime('pbip')->nullable();
            $table->unique(['person_id', 'company_id']);
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
        Schema::dropIfExists('company_people');
    }
}
