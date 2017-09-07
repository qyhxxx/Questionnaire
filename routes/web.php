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

Route::get('login', ['uses' => 'LoginController@login']);
Route::get('loginStatus', ['uses' => 'LoginController@loginStatus']);
Route::get('index', function () {
    return view('index');
})->middleware('CheckToken');

Route::group(['middleware' => ['Authentication']], function () {
    Route::group(['prefix' => 'edit'], function () {
        Route::any('/', ['uses' => 'QuestionnaireController@add']);
        Route::any('qnid/{qnid}', ['uses' => 'QuestionnaireController@addQuestion']);
    });
    Route::any('submit/qnid/{qnid}', ['uses' => 'QuestionnaireController@submit']);
    Route::get('logout', ['uses' => 'LogoutController@logout']);
});
