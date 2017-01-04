<?php
/**
 * Created by PhpStorm.
 * User: lxl
 * Date: 16-11-7
 * Time: 下午2:04
 */

namespace App\Console\Commands\Conversion;

use App\Jobs\Conversion\UserConversionJob;
use App\Models\Conversion\GpaUser;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CourseConversion extends Command
{
    /**
     * 控制台命令名称
     *
     * @var string
     */
    protected $signature = 'conversion:course';

    /**
     * 控制台命令描述
     *
     * @var string
     */
    protected $description = '课程表数据迁移';


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

        $users = GpaUser::all();
        $i = 0;
        foreach ($users as $user) {
            dispatch(new UserConversionJob($user));
            $i++;
        }
        $msg = "成功添加 {$i} 条数据到后台转换队列 \r\n";
        echo $msg;
        Log::info($msg);

    }

}