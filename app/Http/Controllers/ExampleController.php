<?php

namespace App\Http\Controllers;

use App\Jobs\EmailJob;
use Illuminate\Support\Facades\Mail;
class ExampleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function emailTest(){

//        $this->dispatch(new EmailJob());
        $a=Mail::send('emails.email',['testVar'=>123],function ($m){
            $b=$m->to('306755605@qq.com')->subject('测试');
//            print_r($b);
        });
        print_r($a);
    }

    //
}
