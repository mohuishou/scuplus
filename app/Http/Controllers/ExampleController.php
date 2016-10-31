<?php

namespace App\Http\Controllers;

use App\Jobs\EmailJob;
use App\Jobs\Message\MessageJob;
use App\Model\User;
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

    public function test(){

        $user=User::find(10);
        $this->dispatch(new MessageJob($user,"grade",[]));
    }

    //
}
