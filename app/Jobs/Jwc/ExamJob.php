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
    public function __construct(User $user, $is_notify=false, $first="email")
    {
        parent::__construct($user, $is_notify, $first);
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
        if($this->_is_notify && $res["status"]==1){
            dispatch(new MessageJob($this->_user,$this->_template_name,$res["data"],$this->_first));
        }
    }
}
