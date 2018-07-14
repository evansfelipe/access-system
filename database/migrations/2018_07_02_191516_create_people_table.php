<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->string('last_name', 50)->required();
            $table->string('name', 50)->required();
            $table->integer('document_type')->unsigned()->required();
            $table->string('document_number', 15)->required();
            $table->string('cuil', 15)->unique()->required();
            $table->datetime('birthday')->nullable();
            $table->char('sex', 1)->nullable();
            $table->string('blood_type',3)->nullable();
            $table->string('picture_name')->default('');
            $table->json('extra')->nullable();
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
