<?php
/**
 * 教务处相关路由
 */


/**
 * 无需认证
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
 * iCal文件下载
 */
$app->get('/download/ics/{file_name}',[
    'as'=>'download.ics',
    'uses'=>'Jwc\ScheduleController@icsDownload'
]);

/**
 * 需要认证
 */
$app->group(['prefix'=>'/jwc','middleware' => 'auth','namespace' => 'App\Http\Controllers\Jwc'], function() use ($app) {

    $app->post('/course/one', [
        'as' => 'jwc.course.one',
        'uses' => 'CourseController@getOne'
    ]);



    $app->get('/schedule',[
        'as'=>'jwc.schedule.show',
        'uses'=>'ScheduleController@index'
    ]);

    $app->get('/schedule/ics',[
        'as'=>'jwc.schedule.ics',
        'uses'=>'ScheduleController@ics'
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


});
/**
 * 需要用户登录&绑定教务处
 */
$app->group(['namespace' => 'App\Http\Controllers\Jwc'],function () use ($app){

    $app->post('/grade',[
        'as'=>'jwc.grade.update',
        'uses'=>'GradeController@update'
    ]);

    $app->post('/exam',[
        'as'=>'jwc.exam.update',
        'uses'=>'ExamController@update'
    ]);

    $app->post('/schedule',[
        'as'=>'jwc.schedule.update',
        'uses'=>'ScheduleController@update'
    ]);
});

