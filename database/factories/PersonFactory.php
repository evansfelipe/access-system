<?php

use Faker\Generator as Faker;

$factory->define(App\Person::class, function (Faker $faker) {
    return [
        'last_name' => $faker->name,
        'name' => $faker->name,
        'cuil' => strval($faker->unique()->randomNumber($nbDigits = 6)) . strval($faker->unique()->randomNumber($nbDigits = 6)),
        'sex' => 'M',
        'company_id' => rand(1, 15),
        'birthday' => $faker->dateTime($max = 'now')
    ];
});
