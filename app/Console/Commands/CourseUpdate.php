<?php
/**
 * Created by PhpStorm.
 * User: lxl
 * Date: 16-11-7
 * Time: 下午2:04
 */

namespace App\Console\Commands;

use App\Jobs\Jwc\CourseUpdateJob;
use App\Models\User\UserJwc;
use Illuminate\Console\Command;

class CourseUpdate extends Command
{
    /**
     * 控制台命令名称
     *
     * @var string
     */
    protected $signature = 'admin:course {page_number} ';

    /**
     * 控制台命令描述
     *
     * @var string
     */
    protected $description = '更新课程表数据';


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
    public function handle(UserJwc $userJwc)
    {
        $page_number=$this->argument('page_number');
        $users=$userJwc->where('verify',1)->where('id','>',200)->take($page_number)->get();
        for ($i=1;$i<=$page_number;$i++){
            $user=$users[$i-1];
            dispatch(new CourseUpdateJob($user->student_id,decrypt($user->password),$i));
        }
    }

}