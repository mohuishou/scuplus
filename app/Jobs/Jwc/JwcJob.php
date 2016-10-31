<?php

namespace App\Jobs\Jwc;


use App\Model\User;

class JwcJob extends BaseJob
{
    public function __construct(User $user, $is_notify=false, $first="email")
    {
        parent::__construct($user, $is_notify, $first);
    }

    /**
     *
     */
    public function handle()
    {
        $exam=(new ExamJob($this->_user,$this->_is_notify,$this->_first))->onQueue("jwc");
        dispatch($exam);
        $grade=(new GradeJob($this->_user,$this->_is_notify,$this->_first))->onQueue("jwc");
        dispatch($grade);
        $schedule=(new ScheduleJob($this->_user,$this->_is_notify,$this->_first))->onQueue("jwc");
        dispatch($schedule);
    }
}
