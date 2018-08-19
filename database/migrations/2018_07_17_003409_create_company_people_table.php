<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\PersonCompany;

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
            $table->integer('company_id')->unsigned()->nullable();
            $table->integer('activity_id')->unsigned()->required();
            $table->json('subactivities');
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
