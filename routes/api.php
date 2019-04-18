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

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', 'AuthController@login');
    Route::post('signup_company', 'AuthController@signup_company');
    Route::get('states_municipalities', 'StatesController@get_states_municipalities');

    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');
    });
});

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('delivery_men/{delivery_man}/show', 'DeliveryManController@show')->name('deliveries.show');

    Route::get('deliveries', 'DeliveryController@index')->name('deliveries.index');
    Route::get('deliveries/{delivery}/show', 'DeliveryController@show')->name('deliveries.show');
    Route::get('deliveries/{delivery}/detail', 'DeliveryController@detail')->name('deliveries.detail');

    Route::get('delivery_men/service_ranges', 'DeliveryManController@get_service_ranges');

    Route::post('delivery_products', 'DeliveryProductController@store')->name('delivery_products.store');
});

Route::group(['middleware' => ['auth:api', 'role:admin']], function () {
    Route::get('users', 'UserController@index')->name('users.index');
    Route::post('users', 'UserController@store')->name('users.store');
    Route::get('users/{user}', 'UserController@show')->name('users.show');
    Route::put('users/{user}', 'UserController@update')->name('users.update');
    Route::delete('users/{user}', 'UserController@delete')->name('users.delete');

    Route::get('companies', 'CompanyController@index')->name('companies.index');
    Route::post('companies', 'CompanyController@store')->name('companies.store');
    Route::get('companies/{company}', 'CompanyController@show')->name('companies.show');
    Route::put('companies/{company}', 'CompanyController@update')->name('companies.update');
    Route::delete('companies/{company}', 'CompanyController@delete')->name('companies.delete');

    Route::post('delivery_men', 'DeliveryManController@store')->name('delivery_men.store');
    Route::get('delivery_men', 'DeliveryManController@index')->name('delivery_men.index');
    Route::get('delivery_men/get_available_delivery_man', 'DeliveryManController@get_available_delivery_man')->name('delivery_men.get_available_delivery_man');

    Route::get('deliveries/{delivery}/cancel', 'DeliveryController@cancel')->name('deliveries.cancel');
    Route::put('deliveries/{delivery}/assign_delivery_man', 'DeliveryController@assign_delivery_man')->name('deliveries.assign_delivery_man');
});

Route::group(['middleware' => ['auth:api', 'role:client']], function () {
    Route::post('deliveries', 'DeliveryController@store')->name('deliveries.store');
});
