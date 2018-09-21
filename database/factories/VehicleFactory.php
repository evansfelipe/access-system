<?php

use Faker\Generator as Faker;

use Illuminate\Http\Request;

use App\Company;

$factory->define(App\Vehicle::class, function (Faker $faker) {
    $types = ['Auto', 'Camion', 'Remolque', 'Tractor'];
    $brands = ['Renault', 'Fiat', 'Volkswagen', 'Chevrolet'];
    $models = ['AJJ-2345', 'KLS-99', 'Celsiu24', 'Salesi99', 'Colues', 'Samews'];
    $company = Company::all()->random();
    return [
        'company_id' => $company->id,
        'type_id' => 1,
        'owner' => $faker->lastName .' '. $faker->firstName,
        'plate' => $faker->ean8,
        'brand' => $brands[rand()%count($brands)],
        'model' => $models[rand()%count($models)],
        'year' => $faker->year,
        'colour' => $faker->safeColorName,
        'insurance' => $faker->dateTime($min = 'now'),
        'vtv' => $faker->dateTime($min = 'now'),
    ];
});

