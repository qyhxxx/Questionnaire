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
    //
})->middleware('CheckToken');

Route::get('login', 'LoginController@login');
Route::get('loginStatus', 'LoginController@loginStatus');

Route::group(['middleware' => ['Authentication']], function () {
    Route::group(['prefix' => 'edit'], function () {
        Route::any('/', 'QuestionnaireController@add');
        Route::any('qnid/{qnid}', 'QuestionnaireController@addQuestion');
        Route::post('updata/qnid/{qnid}', 'QuestionnaireController@update');
    });
    Route::any('submit/qnid/{qnid}', 'QuestionnaireController@submit');
    Route::group(['prefix' => 'stat/qnid/{qnid}'], function () {
        Route::get('getStems', 'StatisticsController@getStems');
        Route::post('getOptions', 'StatisticsController@getOptions');
        Route::post('statistics', 'StatisticsController@statistics');
    });
    Route::get('logout', 'LogoutController@logout');
});

Route::get('test', 'QuestionnaireController@test');