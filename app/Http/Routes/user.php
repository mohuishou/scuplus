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

/**
 * 无需用户登录
 */
$app->group(['namespace'=>'App\Http\Controllers\User'],function () use ($app){

    //发送验证码
    $app->post('/verify/send/{type}',[
        'as'=>'user.verify.send',
        'uses'=>'UserController@sendVerifyCode'
    ]);

    //检测用户是否存在
    $app->post('/user/check',[
        'as'=>'user.check',
        'uses'=>'UserController@checkUser'
    ]);

    //登录
    $app->post('/login/{type}',[
        'as'=>'user.login',
        'uses'=>'UserController@login'
    ]);

});

/**
 * 需要验证验证码的路由
 */
$app->group(['namespace'=>'App\Http\Controllers\User','middleware' => 'verify_code'],function () use($app){

    //注册
    $app->post('/register/{type}',[
        'as'=>'user.register',
        'uses'=>'UserController@register'
    ]);

    //重置密码
    $app->post('/user/password',[
        'as'=>'user.password.update',
        'uses'=>'UserController@updatePassword'
    ]);
});

/**
 * 需要验证码，并且用户登录的路由
 */
$app->group(['namespace'=>'App\Http\Controllers\User','middleware' => ['verify_code','auth']],function () use($app){

    //绑定检查
    $app->post("/user/bind/check",[
        'as'=>'user.bind.check',
        'uses'=>'UserController@bindCheck'
    ]);

    //绑定
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

    //获取用户信息
    $app->get('/user',[
        'as'=>'user.info',
        'uses'=>'UserInfoController@index'
    ]);

    //更新用户信息
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

    //刷新token
    $app->get('/token/refresh',[
        'as'=>'user.token.refresh',
        'uses'=>'UserController@refreshToken'
    ]);

    //教务处通知设置
    $app->post("/user/notify/jwc",[
        'as'=>'user.notify.jwc',
        'uses'=>'UserNotifyController@jwc'
    ]);

    //图书馆通知设置
    $app->post("/user/notify/library",[
        'as'=>'user.notify.library',
        'uses'=>'UserNotifyController@library'
    ]);

    //默认消息通知方式
    $app->post("/user/notify/first",[
        'as'=>'user.notify.first',
        'uses'=>'UserNotifyController@first'
    ]);

});