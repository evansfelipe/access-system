<?php
use Faker\Generator as Faker;

$factory->define(App\Company::class, function (Faker $faker) {
    $option = ['S.R.L.', 'S.A.'];
    $cuit_options = ['30', '33', '34'];
    $residency = factory(App\Residency::class)->create();
    $name = $faker->unique()->company;

    return [
        'business_name' => $name . ' ' . $option[rand()%count($option)],
        'name' => $name,
        'area' => $faker->word,
        'cuit' => $cuit_options[rand()%count($cuit_options)] .'-'. strval($faker->unique()->randomNumber($nbDigits = 4)) . strval($faker->unique()->randomNumber($nbDigits = 4)) .'-'. strval($faker->unique()->randomNumber($nbDigits = 1)),
        'expiration' => $faker->dateTime($min = 'now'),
        'residency_id' => $residency->id,
        'contact' => json_encode([
            'web'   => $faker->domainName,        
            'fax'   => $faker->e164PhoneNumber,
            'phone' => $faker->e164PhoneNumber,
            'email' => $faker->email  
        ]),
    ];
});
