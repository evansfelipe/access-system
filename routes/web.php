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
        Route::get('/updated-at',                   'PeopleController@updatedAt')->name('people.updated-at');
        Route::get('/list',                         'PeopleController@list')->name('people.list');
        Route::get('/{person}/pictures',            'PeopleController@pictures')->name('people.pictures');
        Route::post('/{person}/new-observation',    'PeopleController@newObservation')->name('people.new-observation');
        Route::get('/document/{person_document}',          'PeopleController@document')->name('people.document');
    }); Route::resource('/people',                  'PeopleController')->only(['store', 'show', 'edit', 'update', 'destroy']);
    // Companies routes
    Route::prefix('companies')->group(function() {
        Route::get('/updated-at',       'CompaniesController@updatedAt')->name('companies.updated-at');
        Route::get('/list',             'CompaniesController@list')->name('companies.list');
    }); Route::resource('/companies',   'CompaniesController')->only(['store', 'show', 'edit', 'update', 'destroy']);
    // Vehicles routes
    Route::prefix('vehicles')->group(function() {
        Route::get('/updated-at',       'VehiclesController@updatedAt')->name('vehicles.updated-at');
        Route::get('/list',             'VehiclesController@list')->name('vehicles.list');
    }); Route::resource('/vehicles',    'VehiclesController')->only(['store', 'show', 'edit', 'update', 'destroy']);
    // Activities routes
    Route::prefix('activities')->group(function() {
        Route::get('/updated-at',       'ActivitiesController@updatedAt')->name('activities.updated-at');
        Route::get('/list',             'ActivitiesController@list')->name('activities.list');
    }); Route::resource('/activities',  'ActivitiesController')->only(['store', 'show', 'edit', 'update', 'destroy']);;

    // Route::resource('/cards', 'CardsController');
});
/**
 * Security user routes.
 */
Route::middleware(['auth', 'user.security'])->prefix('security')->group(function() {
    Route::get('/person/{card_number}',             'SecurityController@personInfo');
    Route::post('/person/{person}/new-observation', 'PeopleController@newObservation');
});
