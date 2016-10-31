<?php

namespace App\Jobs\Jwc;


use App\Http\Controllers\Jwc\GradeController;
use App\Jobs\Message\MessageJob;
use App\Model\User;

class GradeJob extends BaseJob
{
    public function __construct(User $user, $is_notify, $first)
    {
        parent::__construct($user, $is_notify, $first);
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
        if($this->_is_notify && $res["status"]==1){
            dispatch(new MessageJob($this->_user,$this->_template_name,$res["data"],$this->_first));
        }
    }
}
