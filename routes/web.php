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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['namespace' => 'Admin', 'middleware' => ['admin']], function () {
    Route::get('/', function () {
        return view('layouts');
    });
    Route::group(['prefix' => 'questionnaire'], function () {
        Route::get('list', 'QuestionnaireManagementController@listOfQuestionnaires');
        Route::get('deletedList', 'QuestionnaireManagementController@deletedList');
        Route::get('check/{qnid}', 'QuestionnaireManagementController@check');
        Route::get('softDelete/{qnid}', 'QuestionnaireManagementController@softDelete');
        Route::get('restore/{qnid}', 'QuestionnaireManagementController@restore');
        Route::get('forceDelete/{qnid}/{src?}', 'QuestionnaireManagementController@forceDelete');
    });
    Route::group(['prefix' => 'user'], function () {
        Route::get('list', 'UserController@listOfUsers');
        Route::get('toSupMng/{twt_name}', 'UserController@toSupMng');
        Route::get('toOrdMng/{twt_name}', 'UserController@toOrdMng');
    });
});
