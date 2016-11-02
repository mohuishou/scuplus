<?php

namespace App\Console;

use App\Jobs\Jwc\JwcJob;
use App\Jobs\Library\LibraryJob;
use App\Model\User;
use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //每天凌晨两点开始更新所有用户的教务处与图书馆相关信息
        $schedule->call(function (){
            $users=User::all();
            foreach ($users as $user){
                //判断教务处是否绑定
                $jwc=$user->userJwc;
                if(isset($jwc->verify)&&$jwc->verify==1){
                    //分配到教务处更新队列
                    dispatch(new JwcJob($user,true));
                }

                //判断图书馆是否绑定
                $user_library=$user->userLibrary;
                if(isset($user_library->verify)||$user_library->verify==1){
                    //分配到图书馆更新队列
                    dispatch(new LibraryJob($user,true));
                }
            }
        })->dailyAt("2:00");
    }
}
