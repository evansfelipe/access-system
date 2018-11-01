<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DeviceInit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'device:mqtt {data}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function validateStructureOrDie($json, $fields)
    {
        $is_valid = true;
        foreach ($fields as $field) {
            $is_valid = $is_valid && property_exists($json, $field);
        }

        if(!$is_valid)
            die('Invalid Structure' . PHP_EOL);
    }

    public function initHandler($name, $area, $date)
    {
        dd($name, $area, $date);
    }

    public function openHandler($name, $area, $id, $action, $date)
    {
        dd($name, $area, $id, $action, $date);
    }

    public function pongHandler($name, $date, $initial_date, $last_id, $last_id_date, $last_action, $status)
    {
        dd($name, $date, $initial_date, $last_id, $last_id_date, $last_action, $status);
    }

    public function alertHandler($name, $area, $date)
    {
        dd($name, $area, $date);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $data = json_decode($this->argument('data'));
        $this->validateStructureOrDie($data, ['C']);
        switch ($data->C) {
            case 'INICIO':
                $this->validateStructureOrDie($data, ['N', 'A', 'F']);
                $this->initHandler($data->N, $data->A, $data->F);
                break;
            case 'APERTURA':
                $this->validateStructureOrDie($data, ['N', 'A', 'ID', 'Ac', 'F']);
                $this->openHandler($data->N, $data->A, $data->ID, $data->Ac, $data->F);
                break;
            case 'PONG':
                $this->validateStructureOrDie($data, ['N', 'F', 'FA', 'UID', 'UFID', 'UA', 'S']);
                $this->pongHandler($data->N, $data->F, $data->FA, $data->UID, $data->UFID, $data->UA, $data->S);
                break;
            case 'ALARMA':
                $this->validateStructureOrDie($data, ['N', 'A', 'F']);
                $this->alertHandler($data->N, $data->A, $data->F);
        }
    }
}
