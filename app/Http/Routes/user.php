<?php
/**
 * Created by PhpStorm.
 * User: lxl
 * Date: 16-11-2
 * Time: 下午6:55
 */

/**
 * 用户相关操作
 */
$app->group(['namespace'=>'App\Http\Controllers\User'],function () use ($app){

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

    //绑定教务处
    $app->post('/jwc/bind',[
        'as'=>'jwc.bind',
        'uses'=>'UserBindController@jwc'
    ]);

    //绑定图书馆
    $app->post('/library/bind',[
        'as'=>'library.bind',
        'uses'=>'UserBindController@library'
    ]);

    $app->get('/user',[
        'as'=>'user.info',
        'uses'=>'UserInfoController@index'
    ]);

    $app->post('/user/info',[
        'as'=>'user.info',
        'uses'=>'UserInfoController@update'
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