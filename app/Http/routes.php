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
//define("BASE",'/project/scuplus-5.2/public');
$app->get('/',function (){
    return response()->json('welcome to scuplus api');
});

/**
 * 用户相关操作
 */
$app->group(['namespace'=>'App\Http\Controllers\User'],function ($app){
    $app->post('/register/{type}',[
        'as'=>'user.register',
        'uses'=>'UserController@register'
    ]);

    $app->get('/register/{type}/verify/{verify_code}',[
        'as'=>'user.verify',
        'uses'=>'UserController@verify'
    ]);

    $app->post('/verify/resend/{type}',[
        'as'=>'user.verify.resend',
        'uses'=>'UserController@resendVerify'
    ]);

    $app->get('/user/password/{verify_code}',[
        'as'=>'user.password.reset',
        'uses'=>'UserController@resetPassword'
    ]);

    $app->post('/user/password/verify/{type}',[
        'as'=>'user.password.verify',
        'uses'=>'UserController@resetPasswordVerify'
    ]);

    $app->post('/login/{type}',[
        'as'=>'user.login',
        'uses'=>'UserController@login'
    ]);

});

/**
 * 需要认证的用户相关操作
 */
$app->group(['namespace'=>'App\Http\Controllers\User','middleware' => 'auth'],function () use($app){
    $app->post('/jwc/bind',[
        'as'=>'jwc.bind',
        'uses'=>'UserInfoController@bindJwc'
    ]);

    $app->post('/user/password',[
        'as'=>'user.password.update',
        'uses'=>'UserController@updatePassword'
    ]);

    $app->get('/user',[
        'as'=>'user.info',
        'uses'=>'UserInfoController@index'
    ]);

    $app->get('/token/refresh',[
        'as'=>'user.token.refresh',
        'uses'=>'UserController@refreshToken'
    ]);



});

/**
 * 教务处相关操作
 */
$app->group(['prefix'=>'/jwc','middleware' => 'auth','namespace' => 'App\Http\Controllers\Jwc'], function() use ($app) {
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

    $app->post('/evaluate/store',[
        'as'=>'jwc.evaluate.store',
        'uses'=>'EvaluateController@store'
    ]);

    $app->post('/evaluate/update',[
        'as'=>'jwc.evaluate.store',
        'uses'=>'EvaluateController@update'
    ]);

    $app->get('/evaluate',[
        'as'=>'jwc.evaluate.show',
        'uses'=>'EvaluateController@index'
    ]);
});

