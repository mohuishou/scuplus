<?php

namespace App\Jobs\Jwc;


use App\Http\Controllers\Jwc\ScheduleController;
use App\Jobs\Message\MessageJob;
use App\Model\User;

class ScheduleJob extends BaseJob
{

    /**
     * ScheduleJob constructor.
     * @param User $user
     * @param bool $is_notify
     * @param string $first
     */
    public function __construct(User $user,$is_notify=false,$first="email")
    {
        parent::__construct($user,$is_notify,$first);
        if ($is_notify){
            $this->_template_name="schedule";
        }
    }

    /**
     * @param ScheduleController $schedule
     */
    public function handle(ScheduleController $schedule)
    {
        $res=$schedule->updateBase($this->_user);
        if($this->_is_notify && $res["status"]==1){
            dispatch(new MessageJob($this->_user,$this->_template_name,$res["data"],$this->_first));
        }
    }
}
