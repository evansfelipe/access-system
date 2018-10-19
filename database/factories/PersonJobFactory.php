<?php
use Faker\Generator as Faker;
use App\{ Group, Company, Activity };

$factory->define(App\PersonCompany::class, function(Faker $faker) {
    $company = Company::all()->random();
    $activity = Activity::all()->random();
    $subactivities = [];
    for($i = 0; $i < rand(0,5); $i++) { array_push($subactivities, $faker->word); }
    return [
        'person_id'         => null, // Must be replaced
        'company_id'        => rand(0,100) > 50 ? $company->id : null,
        'company_note_id'   => null,
        'activity_id'       => $activity->id,
        'art_company'       => $faker->company,
        'art_number'        => $faker->randomNumber($nbDigits = 4),
        'subactivities'     => json_encode($subactivities),
    ];
});

$factory->afterCreating(App\PersonCompany::class, function($job, $faker) {
    $group = Group::where('company_id', $job->company_id)->orWhere('company_id', null)->get()->random();
    factory(App\Card::class)->create(['person_company_id' => $job->id]);
    factory(App\PersonJobGroup::class)->create(['job_id' => $job->id, 'group_id' => $group->id]);
});

$factory->define(App\PersonJobGroup::class, function(Faker $faker) {
    return [];
});