<?php

use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(App\Card::class, function (Faker $faker) {
    $now = Carbon::now();
    return [
        'number'            => $faker->unique()->randomNumber($nbDigits = 5),
        'person_company_id' => 1,
        'active'            => true,
        'from'              => $now->copy()->format('Y-m-d H:i:s'),
        'until'             => $now->copy()->addDays(20)->format('Y-m-d H:i:s')
    ];
});
