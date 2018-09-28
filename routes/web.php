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
        Route::get('/provinces/{country_id?}',  'LocationsController@provinces')->name('locations.provinces');
        Route::get('/cities/{province_id?}',    'LocationsController@cities')->name('locations.cities');
    });
});
/**
 * Administration user routes.
 */
Route::middleware(['auth','user.administration'])->group(function() {

    // People routes
    Route::prefix('people')->group(function() {
        Route::get('/updated-at',                   'ListsController@peopleUpdatedAt')->name('people.updated-at');
        Route::get('/list',                         'ListsController@peopleList')->name('people.list');
        Route::get('/{person}/pictures',            'PeopleController@pictures')->name('people.pictures');
        Route::get('/document/{person_document}',   'PeopleController@document')->name('people.document');
        Route::get('/{person}/pdf',                 'PeopleController@pdf')->name('people.pdf');
        Route::post('/{person}/new-observation',    'PeopleController@newObservation')->name('people.new-observation');
    }); Route::resource('/people',                  'PeopleController')->only(['store', 'show', 'edit', 'update', 'destroy']);

    // Companies routes
    Route::prefix('companies')->group(function() {
        Route::get('/updated-at',       'ListsController@companiesUpdatedAt')->name('companies.updated-at');
        Route::get('/list',             'ListsController@companiesList')->name('companies.list');
    }); Route::resource('/companies',   'CompaniesController')->only(['store', 'show', 'edit', 'update', 'destroy']);

    // Vehicles routes
    Route::prefix('vehicles')->group(function() {
        Route::get('/updated-at',       'ListsController@vehiclesUpdatedAt')->name('vehicles.updated-at');
        Route::get('/list',             'ListsController@vehiclesList')->name('vehicles.list');
    }); Route::resource('/vehicles',    'VehiclesController')->only(['store', 'show', 'edit', 'update', 'destroy']);

    // Containers routes
    Route::prefix('containers')->group(function() {
        Route::get('/updated-at',       'ListsController@containersUpdatedAt')->name('containers.updated-at');
        Route::get('/list',             'ListsController@containersList')->name('containers.list');
    }); Route::resource('/containers',  'ContainersController')->only(['store', 'show', 'edit', 'update', 'destroy']);
    
    // Activities routes
    Route::prefix('activities')->group(function() {
        Route::get('/updated-at',       'ListsController@activitiesUpdatedAt')->name('activities.updated-at');
        Route::get('/list',             'ListsController@activitiesList')->name('activities.list');
    });

    // Subactivities routes
    Route::prefix('subactivities')->group(function() {
        Route::get('/updated-at',       'ListsController@subactivitiesUpdatedAt')->name('subactivities.updated-at');
        Route::get('/list',             'ListsController@subactivitiesList')->name('subactivities.list');
    });

    // Vehicle types routes
    Route::prefix('vehicle_types')->group(function() {
        Route::get('/updated-at',       'ListsController@vehicleTypesUpdatedAt')->name('vehicle-types.updated-at');
        Route::get('/list',             'ListsController@vehicleTypesList')->name('vehicle-types.list');
    });

    // Groups routes
    Route::prefix('groups')->group(function() {
        Route::get('/updated-at',       'ListsController@groupsUpdatedAt')->name('groups.updated-at');
        Route::get('/list',             'ListsController@groupsList')->name('groups.list');
    }); Route::resource('/groups',  'GroupsController')->only(['store', 'show', 'edit', 'update', 'destroy']);
    
    // Gates routes
    Route::prefix('gates')->group(function() {
        Route::get('/updated-at',   'ListsController@gatesUpdatedAt')->name('gates.updated-at');
        Route::get('/list',         'ListsController@gatesList')->name('gates.list');
    });

    // Settings Routes
    Route::prefix('settings')->group(function() {
        Route::post('new-activity',                 'SettingsController@newActivity')->name('settings.new-activity');
        Route::put('update-activity/{activity}',    'SettingsController@updateActivity')->name('settings.update-activity');
        Route::post('new-subactivity',   'SettingsController@newSubactivity')->name('settings.new-subactivity');
        Route::put('update-subactivity/{subactivity}',    'SettingsController@updateSubactivity')->name('settings.update-subactivity');
        Route::post('new-vehicle-type',                 'SettingsController@newVehicleType')->name('settings.new-vehicle-type');
        Route::put('update-vehicle-type/{vehicle_type}',    'SettingsController@updateVehicleType')->name('settings.update-vehicle-type');
    });

    // Route::resource('/cards', 'CardsController');
});
/**
 * Security user routes.
 */
Route::middleware(['auth', 'user.security'])->prefix('security')->group(function() {
    Route::get('/person/{card_number}',             'SecurityController@personInfo');
    Route::post('/person/{person}/new-observation', 'PeopleController@newObservation');
});
