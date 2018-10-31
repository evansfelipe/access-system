<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();
/**
 * Landing routes.
 */
Route::get('/', function () { return redirect()->route('home'); });
Route::get('/home', 'HomeController@index')->name('home');
/**
 * General application routes.
 */
Route::middleware(['auth'])->prefix('app')->group(function() {
    // Locations routes.
    Route::prefix('locations')->group(function() {
        Route::get('/countries',                'LocationsController@countries')->name('locations.countries');

        Route::get('/countries/updated-at',     'LocationsController@countriesUpdatedAt')->name('locations.countries.updated-at');
        Route::get('/countries/list',           'LocationsController@countries')->name('locations.countries');

        Route::get('/provinces/{country_id?}',  'LocationsController@provinces')->name('locations.provinces');
        Route::get('/cities/{province_id?}',    'LocationsController@cities')->name('locations.cities');
    });
});
/**
 * Administration user routes.
 */
Route::middleware(['auth','user.administration'])->group(function() {

    Route::get('/updated-at/{list}',    'ListsController@updatedAt')->name('lists.updated-at');

    // People routes
    Route::prefix('people')->group(function() {
        Route::get('/list',                         'ListsController@peopleList')->name('people.list');
        Route::get('/{person}/pictures',            'PeopleController@pictures')->name('people.pictures');
        Route::get('/document/{person_document}',   'PeopleController@document')->name('people.document');
        Route::get('/{person}/pdf',                 'PeopleController@pdf')->name('people.pdf');
        Route::post('/{person}/new-observation',    'PeopleController@newObservation')->name('people.new-observation');
        Route::get('/id-search',                    'ListsController@peopleIdSearch')->name('people.id-search');
        Route::post('/{person}/toggle-enabled',     'PeopleController@toggleEnabled')->name('people.toggle-enable');
    }); Route::resource('/people',                  'PeopleController')->only(['store', 'show', 'edit', 'update']);

    // Companies routes
    Route::prefix('companies')->group(function() {
        Route::get('/list',             'ListsController@companiesList')->name('companies.list');
        Route::get('/id-search',        'ListsController@companiesIdSearch')->name('companies.id-search');
    }); Route::resource('/companies',   'CompaniesController')->only(['store', 'show', 'edit', 'update', 'destroy']);

    // Vehicles routes
    Route::prefix('vehicles')->group(function() {
        Route::get('/list',             'ListsController@vehiclesList')->name('vehicles.list');
        Route::get('/id-search',        'ListsController@vehiclesIdSearch')->name('vehicles.id-search');
    }); Route::resource('/vehicles',    'VehiclesController')->only(['store', 'show', 'edit', 'update', 'destroy']);

    // Groups routes
    Route::prefix('groups')->group(function() {
        Route::get('/list',             'ListsController@groupsList')->name('groups.list');
        Route::get('/id-search',        'ListsController@groupsIdSearch')->name('groups.id-search');
    }); Route::resource('/groups',      'GroupsController')->only(['store', 'show', 'edit', 'update', 'destroy']);
    
    // Activities routes
    Route::prefix('activities')->group(function() {
        Route::get('/list',             'ListsController@activitiesList')->name('activities.list');
    });

    // Subactivities routes
    Route::prefix('subactivities')->group(function() {
        Route::get('/list',             'ListsController@subactivitiesList')->name('subactivities.list');
    });

    // Vehicle types routes
    Route::prefix('vehicle_types')->group(function() {
        Route::get('/list',             'ListsController@vehicleTypesList')->name('vehicle-types.list');
    });

    
    // Gates routes
    Route::prefix('gates')->group(function() {
        Route::get('/list',         'ListsController@gatesList')->name('gates.list');
    });

    // Gates routes
    Route::prefix('zones')->group(function() {
        Route::get('/list',         'ListsController@zonesList')->name('zones.list');
    });

    // Settings Routes
    Route::prefix('settings')->group(function() {
        Route::post('new-zone',                             'SettingsController@newZone')->name('settings.new-zone');
        Route::put('update-zone/{zone}',                    'SettingsController@updateZone')->name('settings.update-zone');
        Route::post('new-gate',                             'SettingsController@newGate')->name('settings.new-gate');
        Route::put('update-gate/{gate}',                    'SettingsController@updateGate')->name('settings.update-gate');
        Route::post('new-activity',                         'SettingsController@newActivity')->name('settings.new-activity');
        Route::put('update-activity/{activity}',            'SettingsController@updateActivity')->name('settings.update-activity');
        Route::post('new-subactivity',                      'SettingsController@newSubactivity')->name('settings.new-subactivity');
        Route::put('update-subactivity/{subactivity}',      'SettingsController@updateSubactivity')->name('settings.update-subactivity');
        Route::post('new-vehicle-type',                     'SettingsController@newVehicleType')->name('settings.new-vehicle-type');
        Route::put('update-vehicle-type/{vehicle_type}',    'SettingsController@updateVehicleType')->name('settings.update-vehicle-type');
    });

    // Route::resource('/cards', 'CardsController');

    Route::prefix('selects')->group(function() {
        Route::get('homelands',     'SelectsController@homelands')->name('selects.homelands');
        Route::get('companies',     'SelectsController@companies')->name('selects.companies');
        Route::get('activities',    'SelectsController@activities')->name('selects.activities');
        Route::get('vehicle-types', 'SelectsController@vehicleTypes')->name('selects.vehicle-types');
        Route::get('subactivities', 'SelectsController@subactivities')->name('selects.subactivities');
    });
});
/**
 * Security user routes.
 */
Route::middleware(['auth', 'user.security'])->prefix('security')->group(function() {
    Route::get('/person/{card_number}',             'SecurityController@personInfo');
    Route::post('/entrance',                        'SecurityController@storeEntrance');
    Route::post('/person/{person}/new-observation', 'PeopleController@newObservation');
});
