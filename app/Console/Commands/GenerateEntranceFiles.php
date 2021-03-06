<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Storage;
use App\{Zone, Gate};

class GenerateEntranceFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'files:entrance {zones?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $br = "\r\n";
        // Parses the arguments
        $arg = $this->argument('zones');
        $zones_id = strlen($arg) > 0 ? explode(',', $arg) : [];
        $zones = count($zones_id) > 0 ? Zone::find($zones_id) : Zone::all();
        // Generates each file
        foreach($zones as $zone) {
            // Path where the file will be stored.
            $file_path = "zones/";
            $file_name = $zone->id;
            $path = $file_path . $file_name . '.csv'; // TODO: write the correct file name and path.
            // Content to store.
            $content = "";
            foreach($zone->groups as $group) {
                foreach($group->jobs as $job) {
                    foreach($job->cards as $card) {
                        // Validates that the person is not suspended, as well as the company and
                        // that the card is not expired.
                        if(true) { // TODO: Change
                            // Card FC + ID
                            $line = '0004'.';'.$card->number.';';
                            // Hours range
                            $line = $line . ($group->end < $group->start ?
                                    $group->start.';'.'23:59:59'.';'.'00:00:00'.';'.$group->end.';' :
                                    $group->start.';'.$group->end.';;;');
                            // Days
                            $bin = $group->daysToArray();
                            $line = $line . implode(';', $bin) . ';';
                            // Gate number.
                            $line = $line . 'FF;';//$gate->id.';';
                            // Risk level.
                            $line = $line . $job->person->risk;
                            // Appends the line to the file content.
                            $content = $content . $line . $br;
                        }
                    }
                }
            }
            // Stores the file.
            Storage::put($path, $content);
        }
    }
}
