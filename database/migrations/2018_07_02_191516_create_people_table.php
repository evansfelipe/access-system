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
            $table->boolean('enabled')->default(true);
            $table->string('picture_name')->default('');
            $table->string('last_name', Person::LENGTHS['last_name']['max'])->required();
            $table->string('name', Person::LENGTHS['name']['max'])->required();
            $table->integer('document_type')->unsigned()->required();
            $table->string('document_number', Person::LENGTHS['document_number']['max'])->required();
            $table->string('cuil', Person::LENGTHS['cuil']['max'])->unique()->nullable();
            $table->datetime('birthday')->nullable();
            $table->char('sex', 1)->nullable();
            $table->string('blood_type', 3)->nullable();
            $table->string('homeland')->nullable();
            $table->integer('risk')->unsigned()->required();
            $table->integer('register_number')->unsigned()->nullable();
            $table->string('pna', Person::LENGTHS['pna']['max'])->nullable();
            $table->json('contact')->nullable();
            $table->json('required_documentation')->required();
            $table->integer('residency_id')->unsigned()->nullable();
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
