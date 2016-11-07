<?php

namespace App\Jobs\Jwc;

use App\Http\Controllers\Jwc\ExamController;
use App\Jobs\Message\MessageJob;
use App\Model\User;

/**
 * 成绩更新队列
 * Class ExamJob
 * @package App\Jobs\Jwc
 */
class ExamJob extends BaseJob
{
    public function __construct(User $user, $is_notify=false)
    {
        parent::__construct($user, $is_notify);
        if ($is_notify){
            $this->_template_name="exam";
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(ExamController $exam)
    {
        $res=$exam->updateBase($this->_user);
        $data=$res["data"];
        $data["username"]=$this->_user->username;
        if($this->_is_notify && $res["status"]==1 && $res["count"]>0){
            //判断用户是否开启通知
            if($this->_user->userNotify->jwc_exam==1){
                $message_job=(new MessageJob($this->_user,$this->_template_name,$data))->onQueue("message");
                dispatch($message_job);
            }
        }
    }
}
