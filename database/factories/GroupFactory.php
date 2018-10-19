<?php
use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(App\Group::class, function (Faker $faker) {
    $now = Carbon::now();
    $zone = App\Zone::all()->random();
    $company = App\Company::all()->random();  
    $days = strval(rand(0,1)) . strval(rand(0,1)) . strval(rand(0,1)) . strval(rand(0,1)) . strval(rand(0,1)) . strval(rand(0,1)) . strval(rand(0,1));  
    $days = chr(bindec($days));
    return [
        'name'          => rand(0,2) > 1 ? null : $faker->company,
        'start'         => $now->copy()->addHours(rand(1,10))->format('H:i:s'),
        'end'           => $now->copy()->addHours(rand(1,10))->format('H:i:s'),
        'zone_id'       => $zone->id,
        'company_id'    => rand(0,100) > 50 ? $company->id : null,
        'days'          => $days,
    ];
});
