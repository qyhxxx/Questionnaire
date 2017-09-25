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
    //
});

Route::get('login', 'LoginController@login');
Route::get('loginStatus', 'LoginController@loginStatus');
Route::any('submit/qnid/{qnid}', 'QuestionnaireController@submit');

Route::group(['middleware' => ['Authentication']], function () {
    Route::group(['prefix' => 'edit'], function () {
        Route::post('status/{status}', 'QuestionnaireController@add');
        Route::post('update/qnid/{qnid}/status/{status}', 'QuestionnaireController@update');
    });
    Route::get('qnid/{qnid}', 'QuestionnaireController@show');
    Route::group(['prefix' => 'stat/qnid/{qnid}'], function () {
        Route::get('getChoiceQuestions', 'StatisticsController@getChoiceQuestions');
        Route::post('getOptions', 'StatisticsController@getOptions');
        Route::get('getQuestions', 'StatisticsController@getQuestions');
        Route::post('statistics', 'StatisticsController@statistics');
    });

    //我的问卷
    Route::group(['prefix' => 'minequestion'], function () {

        //问卷缩略图页面
//        Route::post('/mine', 'MineQuestionController@mine');
        Route::get('/mine', 'MineQuestionController@questionnaire');

        //问卷展开[概述、设置]
        Route::any('/overview/{id}', 'MineQuestionController@overview');
//        Route::get('/overview/{id}', function () {
//            return view('minequestion.overview');
//        });

        //问卷展开[数据]
        Route::get('/answerdata/{id}','MineQuestionController@answerdata');
    });

    Route::get('logout', 'LogoutController@logout');
});

Route::get('test', function () {
    return view('test');
});