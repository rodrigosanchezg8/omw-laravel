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
});
