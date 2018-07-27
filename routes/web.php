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
    Route::resource('/people', 'PeopleController');
    Route::resource('/companies', 'CompaniesController');
    Route::resource('/people/companies', 'PeopleCompaniesController', ['except' => ['create', 'store']], ['as' => 'people']);
    Route::resource('/people/vehicles', 'PeopleVehiclesController', ['except' => ['create', 'store']], ['as' => 'people']);
    Route::resource('/cards', 'CardsController');
    Route::resource('/vehicles', 'VehiclesController');
});

