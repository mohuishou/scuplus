<?php

namespace App\Console;

use App\Http\Controllers\Jwc\EvaluateController;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Log;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        'App\Console\Commands\Conversion\CourseConversion'
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
//        $schedule->call(function (){
//            $eva_update=new EvaluateController();
//            $res=$eva_update->reEvaluate();
//            Log::info("评教复查开始！");
//            Log::info($res['msg']);
//        })->hourly();
    }
    
}
