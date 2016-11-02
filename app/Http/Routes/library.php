<?php
/**
 * Created by PhpStorm.
 * User: lxl
 * Date: 16-11-2
 * Time: 下午6:54
 */

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

    $app->post('/loan/one',[
        'as'=>'library.loan.one',
        'uses'=>'LoanController@one'
    ]);

    $app->post('/loan/all',[
        'as'=>'library.now.all',
        'uses'=>'LoanController@all'
    ]);
});
