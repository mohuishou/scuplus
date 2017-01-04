<?php
/**
 * Created by PhpStorm.
 * User: lxl
 * Date: 16-11-7
 * Time: 下午2:04
 */

namespace App\Console\Commands;

use App\Http\Controllers\Jwc\EvaluateController;
use App\Http\Controllers\User\UserJwcController;
use App\Jobs\UserConversionJob;
use App\Jobs\UserConversionUpdateJob;
use App\Models\GpaUser;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ScuplusEvaluate extends Command{
    /**
     * 控制台命令名称
     *
     * @var string
     */
    protected $signature = 'scuplus:eva';

    /**
     * 控制台命令描述
     *
     * @var string
     */
    protected $description = '用户迁移，获取评教数据';


    /**
     * Scuplus constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 执行控制台命令
     *
     * @return mixed
     */
    public function handle()
    {

            $users=GpaUser::all();
            $i=0;
            foreach ($users as $user){
                dispatch(new UserConversionJob($user));
                $i++;
            }
            $msg="成功添加 {$i} 条数据到后台转换队列";
        echo $msg;
            Log::info($msg);

    }

}