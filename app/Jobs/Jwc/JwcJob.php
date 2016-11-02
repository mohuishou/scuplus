<?php

namespace App\Jobs\Jwc;


use App\Model\User;

class JwcJob extends BaseJob
{
    public function __construct(User $user, $is_notify=false)
    {
        parent::__construct($user, $is_notify);
    }

    /**
     *
     */
    public function handle()
    {
        $exam=(new ExamJob($this->_user,$this->_is_notify))->onQueue("jwc");
        dispatch($exam);

        $grade=(new GradeJob($this->_user,$this->_is_notify))->onQueue("jwc");
        dispatch($grade);

        $schedule=(new ScheduleJob($this->_user,$this->_is_notify))->onQueue("jwc");
        dispatch($schedule);

        $user_info=(new UserInfoJob($this->_user))->onQueue("jwc");
        dispatch($user_info);
    }
}
