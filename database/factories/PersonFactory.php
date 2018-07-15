<?php

use Faker\Generator as Faker;

$factory->define(App\Person::class, function (Faker $faker) {
    $option = ['F', 'M', 'O'];
    return [
        'last_name' => $faker->lastName,
        'name' => $faker->firstName,
        'cuil' => strval($faker->unique()->randomNumber($nbDigits = 6)) . strval($faker->unique()->randomNumber($nbDigits = 6)),
        'sex' => $option[rand()%count($option)],
        'birthday' => $faker->dateTime($max = 'now')
    ];
});
