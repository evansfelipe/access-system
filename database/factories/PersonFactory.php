<?php

use Faker\Generator as Faker;
use App\{ Person, Company };
use Illuminate\Http\Request;
use App\Http\Traits\SaveResidencyTrait;

$factory->define(App\Person::class, function (Faker $faker) {
    $cuil_options = ['30', '33', '34'];
    $genders = ['F', 'M', 'O'];
    $document_types = [Person::DNI, Person::PASSPORT];
    $residency_data = new Request([
        'street'   => $faker->streetAddress,
        'cp'       => $faker->postcode,
        'country'  => $faker->country,
        'province' => $faker->state,
        'city'     => $faker->city,    
    ]);
    $company = Company::all()->random();
    return [
        'last_name' => $faker->lastName,
        'name' => $faker->firstName,
        'document_type' => $document_types[rand()%count($document_types)],
        'document_number' => $faker->ean8,
        'cuil' => $cuil_options[rand()%count($cuil_options)] .'-'. strval($faker->ean8) .'-'. strval($faker->randomNumber($nbDigits = 1)),
        'sex' => $genders[rand()%count($genders)],
        'birthday' => $faker->dateTime($max = 'now'),
        'contact' => json_encode([
            'fax'   => $faker->e164PhoneNumber,
            'home_phone' => $faker->e164PhoneNumber,
            'mobile_phone' => $faker->e164PhoneNumber,
            'email' => $faker->email
        ]),
        'residency_id' => SaveResidencyTrait::saveResidency($residency_data),

    ];
});
