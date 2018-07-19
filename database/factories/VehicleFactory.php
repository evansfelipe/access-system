<?php

use Faker\Generator as Faker;

use Illuminate\Http\Request;

use App\Company;

$factory->define(App\Vehicle::class, function (Faker $faker) {
    $brands = ['Renault', 'Fiat', 'Volkswagen', 'Chevrolet'];
    $models = ['AJJ-2345', 'KLS-99', 'Celsiu24', 'Salesi99', 'Colues', 'Samews'];
    $company = Company::all()->random();
    return [
        'company_id' => $company->id,
        'plate' => $faker->ean8,
        'brand' => $brands[rand()%count($brands)],
        'model' => $models[rand()%count($models)],
        'year' => $faker->year,
        'colour' => $faker->safeColorName,
    ];
});

