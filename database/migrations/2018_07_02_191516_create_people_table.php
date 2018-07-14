<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Person;

class CreatePeopleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('people', function (Blueprint $table) {
            $table->increments('id');
            $table->string('last_name', Person::LENGTHS['last_name']['max'])->required();
            $table->string('name', Person::LENGTHS['name']['max'])->required();
            $table->integer('document_type')->unsigned()->required();
            $table->string('document_number', Person::LENGTHS['document_number']['max'])->required();
            $table->string('cuil', Person::LENGTHS['cuil']['max'])->unique()->required();
            $table->datetime('birthday')->nullable();
            $table->char('sex', 1)->nullable();
            $table->string('blood_type',3)->nullable();
            $table->string('picture_name')->default('');
            $table->string('pna', Person::LENGTHS['pna']['max'])->nullable();
            $table->json('contact')->nullable();
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
        Schema::dropIfExists('people');
    }
}
