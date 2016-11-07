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
        'App\Console\Commands\Scuplus',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $file_path=base_path()."/schedule.log";
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
                if(isset($user_library->verify)&&$user_library->verify==1){
                    //分配到图书馆更新队列
                    dispatch(new LibraryJob($user,true));
                }
            }
        })->dailyAt("8:00")->appendOutputTo($file_path);
//            ->dailyAt("2:00");
    }
}
