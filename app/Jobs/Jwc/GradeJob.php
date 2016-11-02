<?php

namespace App\Jobs\Jwc;


use App\Http\Controllers\Jwc\GradeController;
use App\Jobs\Message\MessageJob;
use App\Model\User;

class GradeJob extends BaseJob
{
    public function __construct(User $user, $is_notify=false)
    {
        parent::__construct($user, $is_notify);
        if ($is_notify){
            $this->_template_name="grade";
        }
    }

    /**
     * @param GradeController $grade
     */
    public function handle(GradeController $grade)
    {
        $res=$grade->updateBase($this->_user);
        if($this->_is_notify && $res["status"]==1 && $res["count"]>0){
            //判断用户是否开启通知
            if($this->_user->userNotify->jwc_grade==1){
                $message_job=(new MessageJob($this->_user,$this->_template_name,$res["data"]))->onQueue("message");
                dispatch($message_job);
            }
        }
    }
}
