<?php

use Faker\Generator as Faker;
use App\VehicleType;

$factory->define(App\Vehicle::class, function (Faker $faker) {
    $types = ['Auto', 'Camion', 'Remolque', 'Tractor'];
    $brands = ['Renault', 'Fiat', 'Volkswagen', 'Chevrolet'];
    $models = ['AJJ-2345', 'KLS-99', 'Celsiu24', 'Salesi99', 'Colues', 'Samews'];
    $company = App\Company::all()->random();
    $type    = App\VehicleType::all()->random();
    return [
        'company_id'    => $company->id,
        'type_id'       => $type->id,
        'owner'         => $faker->lastName .' '. $faker->firstName,
        'plate'         => $faker->ean8,
        'brand'         => $brands[rand()%count($brands)],
        'model'         => $models[rand()%count($models)],
        'year'          => $faker->year,
        'colour'        => $faker->safeColorName,
        'insurance'     => $faker->dateTime($min = 'now'),
        'vtv'           => $faker->dateTime($min = 'now'),
    ];
});

