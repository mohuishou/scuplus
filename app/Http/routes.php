<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
define("BASE",'/project/scuplus-5.2/public');
$app->get(BASE.'/',[
    'as'=>'email.test',
    'uses'=>'EXampleController@emailTest'
]);

/**
 * 用户相关操作
 */
$app->group(['namespace'=>'App\Http\Controllers\User','prefix'=>BASE],function ($app){
    $app->post('/register/{type}',[
        'as'=>'user.register',
        'uses'=>'UserController@register'
    ]);

    $app->get('/register/{type}/verify/{verify_code}',[
        'as'=>'user.verify',
        'uses'=>'UserController@verify'
    ]);

    $app->post('/login/{type}',[
        'as'=>'user.login',
        'uses'=>'UserController@login'
    ]);

});

/**
 * 绑定教务处
 */
$app->group(['prefix'=>BASE.'/jwc','middleware' => 'auth'],function () use($app){
    $app->post('/bind',[
        'as'=>'jwc.bind',
        'uses'=>'App\Http\Controllers\User\UserInfoController@bindJwc'
    ]);


});

/**
 * 教务处相关操作
 */
$app->group(['prefix'=>BASE.'/jwc','middleware' => 'auth','namespace' => 'App\Http\Controllers\Jwc'], function() use ($app) {
//    $app->post('/course',[
//        'as'=>'jwc.course',
//        'uses'=>'CourseController@index'
//    ]);

    $app->post('/schedule',[
        'as'=>'jwc.schedule.update',
        'uses'=>'ScheduleController@update'
    ]);

    $app->get('/schedule',[
        'as'=>'jwc.schedule.show',
        'uses'=>'ScheduleController@index'
    ]);

    $app->post('/grade',[
        'as'=>'jwc.grade.update',
        'uses'=>'GradeController@update'
    ]);

    $app->get('/grade',[
        'as'=>'jwc.grade.show',
        'uses'=>'GradeController@index'
    ]);
});

