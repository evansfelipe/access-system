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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::middleware(['auth','administration'])->group(function() {
    Route::get('/people/updated_at', 'PeopleController@updated_at');
    Route::get('/people/list', 'PeopleController@list');
    Route::get('/people/{person}/pictures', 'PeopleController@pictures');
    Route::post('/people/{person}/new-observation', 'PeopleController@newObservation');
    Route::resource('/people', 'PeopleController');
    Route::get('/companies/updated_at', 'CompaniesController@updated_at');
    Route::get('/companies/list', 'CompaniesController@list');
    Route::resource('/companies', 'CompaniesController');
    // Route::resource('/people/companies', 'PeopleCompaniesController', ['except' => ['create', 'store']], ['as' => 'people']);
    // Route::resource('/people/vehicles', 'PeopleVehiclesController', ['except' => ['create', 'store']], ['as' => 'people']);
    Route::resource('/cards', 'CardsController');
    Route::get('/vehicles/updated_at', 'VehiclesController@updated_at');
    Route::get('/vehicles/list', 'VehiclesController@list');
    Route::resource('/vehicles', 'VehiclesController');
    Route::get('/activities/updated_at', 'ActivitiesController@updated_at');
    Route::get('/activities/list', 'ActivitiesController@list');
    Route::resource('/activities', 'ActivitiesController');
});

// Security
Route::get('/security/person/{card_number}', 'SecurityController@personInfo');
Route::post('/security/person/{person}/new-observation', 'PeopleController@newObservation');
