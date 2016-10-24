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

    $app->post('/verify/send/{type}',[
        'as'=>'user.verify.send',
        'uses'=>'UserController@sendVerifyCode'
    ]);

    //检测用户是否存在
    $app->post('/user/check',[
        'as'=>'user.check',
        'uses'=>'UserController@checkUser'
    ]);

    $app->post('/login/{type}',[
        'as'=>'user.login',
        'uses'=>'UserController@login'
    ]);

});

/**
 * 需要验证验证码的路由
 */
$app->group(['namespace'=>'App\Http\Controllers\User','middleware' => 'verify_code'],function () use($app){
    $app->post('/register/{type}',[
        'as'=>'user.register',
        'uses'=>'UserController@register'
    ]);

    $app->post('/user/password',[
        'as'=>'user.password.update',
        'uses'=>'UserController@updatePassword'
    ]);
});

/**
 * 需要验证码，并且用户登录的路由
 */
$app->group(['namespace'=>'App\Http\Controllers\User','middleware' => ['verify_code','auth']],function () use($app){

    /**
     * 绑定检查
     */
    $app->post("/user/bind/check",[
        'as'=>'user.bind.check',
        'uses'=>'UserController@bindCheck'
    ]);

    /**
     * 绑定
     */
    $app->post("/user/bind/{type}",[
        'as'=>'user.bind',
        'uses'=>'UserController@bind'
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

    $app->get('/user',[
        'as'=>'user.info',
        'uses'=>'UserInfoController@index'
    ]);

    //检测用户是否登录
    $app->get('/login/check',function (){
        return [
            "status"=>1,
            "msg"=>"用户已登录"
        ];
    });

    $app->get('/token/refresh',[
        'as'=>'user.token.refresh',
        'uses'=>'UserController@refreshToken'
    ]);




});

/**
 * iCal文件下载
 */
$app->get('/download/ics/{file_name}',[
    'as'=>'download.ics',
    'uses'=>'Jwc\ScheduleController@icsDownload'
]);

/**
 * 无需认证的教务处相关操作
 */
$app->group(['prefix'=>'/jwc','namespace' => 'App\Http\Controllers\Jwc'], function() use ($app) {

    $app->post('/course', [
        'as' => 'jwc.course',
        'uses' => 'CourseController@index'
    ]);

    $app->post('/teacher', [
        'as' => 'jwc.teacher',
        'uses' => 'CourseController@teacher'
    ]);



});

/**
 * 教务处相关操作
 */
$app->group(['prefix'=>'/jwc','middleware' => 'auth','namespace' => 'App\Http\Controllers\Jwc'], function() use ($app) {
    $app->post('/course/one', [
        'as' => 'jwc.course.one',
        'uses' => 'CourseController@getOne'
    ]);

    $app->post('/schedule',[
        'as'=>'jwc.schedule.update',
        'uses'=>'ScheduleController@update'
    ]);

    $app->get('/schedule',[
        'as'=>'jwc.schedule.show',
        'uses'=>'ScheduleController@index'
    ]);

    $app->get('/schedule/ics',[
        'as'=>'jwc.schedule.ics',
        'uses'=>'ScheduleController@ics'
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

    $app->get('/evaluate/all',[
        'as'=>'jwc.evaluate.all',
        'uses'=>'EvaluateController@getAll'
    ]);

    $app->get('/exam',[
        'as'=>'jwc.exam.show',
        'uses'=>'ExamController@index'
    ]);

    $app->post('/exam',[
        'as'=>'jwc.exam.update',
        'uses'=>'ExamController@update'
    ]);
});

/**
 * 图书馆相关api
 */
$app->group(['prefix'=>'/library','middleware' => 'auth','namespace' => 'App\Http\Controllers\Library'], function() use ($app) {
    $app->post('/history',[
        'as'=>'library.history.update',
        'uses'=>'HistoryController@update'
    ]);

    $app->get('/history',[
        'as'=>'library.history.show',
        'uses'=>'HistoryController@index'
    ]);

    $app->post('/now',[
        'as'=>'library.now.update',
        'uses'=>'NowController@update'
    ]);

    $app->get('/now',[
        'as'=>'library.now.show',
        'uses'=>'NowController@index'
    ]);
});
