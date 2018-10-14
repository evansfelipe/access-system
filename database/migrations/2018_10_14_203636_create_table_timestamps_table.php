<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTimestampsTable extends Migration
{
    protected $tables = ['people', 'companies', 'vehicles', 'activities', 'subactivities', 'vehicle_types', 'groups', 'gates'];
    
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('table_timestamps', function (Blueprint $table) {
            $table->increments('id');
            $table->string('table_name')->required()->unique();
            $table->timestamp('last')->required();
        });

        $query = "
            create trigger _table_last_insert_timestamp after insert on `_table` for each row
            begin
                insert into table_timestamps (`table_name`, `last`)
                values ('_table', now())
                on duplicate key update last=now();
            end;

            create trigger _table_last_update_timestamp after update on `_table` for each row
            begin
                insert into table_timestamps (`table_name`, `last`)
                values ('_table', now())
                on duplicate key update last=now();
            end;
        ";

        foreach($this->tables as $table) {
            DB::unprepared(str_replace('_table', $table, $query));
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('table_timestamps');
        foreach($this->tables as $table) {
            DB::unprepared(str_replace('_table', $table, 'DROP TRIGGER `_table_last_insert_timestamp`'));
            DB::unprepared(str_replace('_table', $table, 'DROP TRIGGER `_table_last_update_timestamp`'));
        }
    }
}
