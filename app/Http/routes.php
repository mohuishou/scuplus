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

$app->get('/', function () use ($app) {
    return $app->version();
});

$app->group(['prefix'=>'/user','namespace' => 'User'], function() use ($app) {
    $app->post('/jwc', [
        'as' => 'user.jwc',
        'uses' => 'UserJwcController@index'
    ]);
});

$app->group(['prefix'=>'/jwc','namespace' => 'Jwc','middleware' => 'auth'], function() use ($app) {
    $app->post('/evaluate/add-job', [
        'as' => 'jwc.evaluate.addJob',
        'uses' => 'EvaluateController@addJob'
    ]);
});

$app->group(['prefix'=>'/jwc','namespace' => 'Jwc'], function() use ($app) {
    $app->post('/evaluate/info', [
        'as' => 'jwc.evaluate.info',
        'uses' => 'EvaluateController@index'
    ]);
});
