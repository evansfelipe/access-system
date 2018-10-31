<?php
use Faker\Generator as Faker;
use App\{ Person, PersonCompany };
use Illuminate\Http\Request;
use Carbon\Carbon;

$factory->define(App\Person::class, function (Faker $faker) {
    $cuil_options = ['30', '33', '34'];
    $genders = ['F', 'M', 'O'];
    $document_types = [Person::DNI, Person::PASSPORT];
    $residency = factory(App\Residency::class)->create();
    $contact = [
        'fax'           => $faker->e164PhoneNumber,
        'email'         => $faker->email,
        'home_phone'    => $faker->e164PhoneNumber,
        'mobile_phone'  => $faker->e164PhoneNumber,
    ];
    return [
        'picture_name'              => '',
        'last_name'                 => $faker->lastName,
        'name'                      => $faker->firstName,
        'document_type'             => $document_types[rand()%count($document_types)],
        'document_number'           => $faker->ean8,
        'cuil'                      => $cuil_options[rand()%count($cuil_options)] .'-'. strval($faker->ean8) .'-'. strval($faker->randomNumber($nbDigits = 1)),
        'birthday'                  => $faker->dateTime($max = 'now'),
        'sex'                       => $genders[rand()%count($genders)],
        'blood_type'                => null,
        'homeland'                  => null,
        'risk'                      => rand(1,3),
        'register_number'           => $faker->ean8,
        'pna'                       => $faker->ean8,
        'contact'                   => json_encode($contact),
        'required_documentation'    => json_encode((object) [
                                        'acc_pers'          => true,
                                        'art_file'          => false,
                                        'boarding_card'     => true,
                                        'boarding_passbook' => false,
                                        'company_note'      => true,
                                        'dni_copy'          => false,
                                        'driver_license'    => true,
                                        'health_notebook'   => false,
                                        'pbip_file'         => true,
                                        'pna_file'          => false
        ]),
        'residency_id'              => $residency->id,
    ];
});

$factory->afterCreating(App\Person::class, function($person, $faker) {
    // Faker needs the folder to exist to save the file. If it doesn't exist we have to create it.
    $dir = storage_path('app/documentation/');
    if (!file_exists($dir) && !is_dir($dir)) {
        mkdir($dir);
    }
    $dir = storage_path('app/documentation/'.($person->id % 10).'/');
    if (!file_exists($dir) && !is_dir($dir)) {
        mkdir($dir);
    }
    $dir = storage_path('app/documentation/'.($person->id % 10).'/'.$person->id.'/');
    if (!file_exists($dir) && !is_dir($dir)) {
        mkdir($dir);
    }
    $dir = storage_path('app/documentation/'.($person->id % 10).'/'.$person->id.'/pictures');
    if (!file_exists($dir) && !is_dir($dir)) {
        mkdir($dir);         
    }     
    // We have to save the image here because we need to use the id of the person for the storage folder.
    $picture_name = $faker->image(storage_path('app/'.$person->getStorageFolder().'pictures/'), 400, 400, null, false);
    $person->picture_name = $picture_name;
    $person->save();
    // Now we are going to create the jobs for this person.
    factory(App\PersonCompany::class)->create(['person_id' => $person->id]);
});