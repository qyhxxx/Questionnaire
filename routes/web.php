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

Route::get('/', function () {
    return view('layouts');
});

Route::get('login', 'LoginController@login');
Route::get('logout', 'LogoutController@logout')->middleware('Authentication');

Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'middleware' => 'Authentication'], function () {
    Route::group(['prefix' => 'questionnaire'], function () {
        Route::get('list', 'QuestionnaireManagementController@listOfQuestionnaires');
        Route::get('deletedList', 'QuestionnaireManagementController@deletedList');
        Route::get('check/{qnid}', 'QuestionnaireManagementController@check');
        Route::get('softDelete/{qnid}', 'QuestionnaireManagementController@softDelete');
        Route::get('restore/{qnid}', 'QuestionnaireManagementController@restore');
        Route::get('forceDelete/{qnid}', 'QuestionnaireManagementController@forceDelete');

        Route::get('test/{qnid}', function ($qnid) {
            \App\Questionnaire::restore($qnid);
            $questionnaires = \App\Questionnaire::paginate(15);
            dd($questionnaires);
        });
    });
    Route::group(['prefix' => 'user'], function () {
        Route::get('list', 'UserController@listOfUsers');
    });
});
