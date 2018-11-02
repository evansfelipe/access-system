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
            $table->integer('company_note_id')->unsigned()->nullable();
            $table->integer('activity_id')->unsigned()->required();
            $table->string('art_company', PersonCompany::LENGTHS['art_company']['max'])->required();
            $table->integer('art_number')->required();
            $table->integer('art_file_id')->unsigned()->nullable();
            $table->json('subactivities');
            // $table->unique(['person_id', 'company_id']);
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('person_id')->references('id')->on('people');

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
