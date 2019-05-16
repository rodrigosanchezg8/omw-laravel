<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('users', 'UserController@store')->name('users.store');

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', 'AuthController@login');
    Route::put('users/{user}', 'UserController@update')->name('users.update');

    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');
    });
});

Route::group(['middleware' => ['auth:api']], function () {

    Route::get('users/{user}', 'UserController@show')->name('users.show');
    Route::put('users/{user}', 'UserController@update')->name('users.update');

    Route::get('deliveries', 'DeliveryController@index')->name('deliveries.index');
    Route::get('deliveries/{delivery}/show', 'DeliveryController@show')->name('deliveries.show');
    Route::put('deliveries/{delivery}/change_status', 'DeliveryController@change_status')->name('deliveries.change_status');


    Route::get('delivery_men/service_ranges', 'DeliveryManController@get_service_ranges')->name('delivery_men.service_ranges');
    Route::get('delivery_men/{delivery_man}/show', 'DeliveryManController@show')->name('delivery_men.show');

    Route::post('locations', 'LocationController@store')->name('locations.store');

    Route::get('delivery_location_tracks', 'DeliveryLocationTrackController@index')->name('delivery_location_tracks.index');
});

Route::group(['middleware' => ['auth:api', 'role:admin']], function () {
    Route::get('users', 'UserController@index')->name('users.index');
    Route::delete('users/{user}', 'UserController@delete')->name('users.delete');

    Route::delete('companies/{company}', 'CompanyController@delete')->name('companies.delete');
});

Route::group(['middleware' => ['auth:api', 'role:admin|client']], function () {

    Route::post('signup_company', 'AuthController@signup_company');

    Route::get('companies', 'CompanyController@index')->name('companies.index');
    Route::post('companies', 'CompanyController@store')->name('companies.store');
    Route::get('companies/{company}', 'CompanyController@show')->name('companies.show');
    Route::put('companies/{company}', 'CompanyController@update')->name('companies.update');
    Route::delete('companies/{company}', 'CompanyController@delete')->name('companies.delete');

    Route::get('delivery_men', 'DeliveryManController@index')->name('delivery_men.index');

    Route::get('deliveries/{delivery}/cancel', 'DeliveryController@cancel')->name('deliveries.cancel');
    Route::put('deliveries/{delivery}/set_not_started_protocol', 'DeliveryController@set_not_started_protocol');
    Route::delete('deliveries/{delivery}', 'DeliveryController@destroy')->name('deliveries.destroy');
    Route::post('deliveries', 'DeliveryController@store')->name('deliveries.store');
    Route::put('deliveries/{delivery}', 'DeliveryController@update')->name('deliveries.update');

    Route::get('messages/delivery/{delivery}/start/{start_message_id}', 'MessageController@index');
    Route::post('messages/delivery/{delivery}', 'MessageController@store');

    Route::get('delivery_products/delivery/{delivery}', 'DeliveryProductController@byDelivery');
    Route::resource('delivery_products', 'DeliveryProductController');

    Route::get('users/clients/by_email', 'UserController@showClientByEmail')->name('users.by_email');

    Route::get('statistics/{user}/client_linear_regression', 'StatisticController@client_linear_regression')->name('statistics.client_linear_regression');
});

Route::group(['middleware' => ['auth:api', 'role:delivery_man']], function () {
    Route::post('delivery_men', 'DeliveryManController@store')->name('delivery_men.store');

    Route::post('delivery_location_tracks', 'DeliveryLocationTrackController@store')->name('delivery_location_tracks.store');
});
