<?php

use Faker\Generator as Faker;

use Illuminate\Http\Request;
use App\Http\Traits\SaveResidencyTrait;

$factory->define(App\Company::class, function (Faker $faker) {
    $option = ['S.R.L.', 'S.A.'];
    $residency_data = new Request([
        'street'   => $faker->streetAddress,
        'cp'       => $faker->postcode,
        'country'  => $faker->country,
        'province' => $faker->state,
        'city'     => $faker->city,    
    ]);
    return [
        'name' => $faker->unique()->company . ' ' . $option[rand()%count($option)],
        'area' => $faker->word,
        'cuit' => strval($faker->unique()->randomNumber($nbDigits = 6)) . strval($faker->unique()->randomNumber($nbDigits = 6)),
        'expiration' => $faker->dateTime($min = 'now'),
        'residency_id' => SaveResidencyTrait::saveResidency($residency_data),
        'contact' => json_encode([
            'phone' => $faker->e164PhoneNumber,
            'email' => $faker->email     
        ]),
    ];
});
