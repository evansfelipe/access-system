<?php

use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(App\Residency::class, function (Faker $faker) {
    $now = Carbon::now();
    return [
        'street'        => $faker->streetAddress,
        'apartment'     => rand(0,10) > 5 ? '1º A' : null,
        'cp'            => $faker->postcode,
        'country'       => $faker->country,
        'province'      => $faker->state,
        'city'          => $faker->city, 
        'created_at'    => $now->copy()->format('Y-m-d H:i:s'),
        'updated_at'    => $now->copy()->format('Y-m-d H:i:s')  
    ];
});
