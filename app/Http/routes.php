<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the Routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
$app->get('/',function (){
    return response()->json('welcome to scuplus api');
});

$app->get('/test',[
    "uses"=>"ExampleController@test"
]);

require_once __DIR__."/Routes/jwc.php";
require_once __DIR__."/Routes/library.php";
require_once __DIR__."/Routes/user.php";


