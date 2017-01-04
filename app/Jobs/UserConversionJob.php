<?php
/**
 * Created by PhpStorm.
 * User: lxl
 * Date: 2016/12/18
 * Time: 23:51
 */

namespace App\Jobs;


use App\Http\Controllers\Jwc\EvaluateController;
use App\Http\Controllers\User\UserJwcController;
use App\Models\GpaUser;
use Illuminate\Support\Facades\Log;

class UserConversionJob extends Job
{
    protected $_user;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(GpaUser $user)
    {
        $this->_user=$user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(EvaluateController $evaluateController,UserJwcController $userJwcController)
    {
        $star=time();
        $user=$this->_user;
        $msg="成功";
        $data=$evaluateController->show($user->school_id,$user->school_password,$userJwcController);
        if($data['status']==1){
            $user->delete();
            $msg="成功";
        }else{
            $res=preg_match_all('/不存在|欠费|不正确/',$data['msg']);
            if($res>0){
                $user->delete();
                $msg="无效账号";
            }else{
                $msg="失败";
            }
        }
        Log::info("[ 用户转换队列 ] 耗时".(time()-$star)."秒，结果：".$msg);
    }
}