<?php

namespace App\Jobs\Library;


use App\Http\Controllers\Jwc\GradeController;
use App\Http\Controllers\Library\HistoryController;
use App\Http\Controllers\Library\NowController;
use App\Jobs\Message\MessageJob;
use App\Model\User;

class LibraryJob extends BaseJob
{
    public function __construct(User $user, $is_notify=false)
    {
        parent::__construct($user, $is_notify);
        if ($is_notify){
            $this->_template_name="library";
        }
    }

    /**
     * @param GradeController $grade
     */
    public function handle(NowController $now,HistoryController $history)
    {
        $res=$now->updateBase($this->_user);
        $data["args"]=$res["data"];
        $data["username"]=$this->_user->username;
        $history->updateBase($this->_user);
        if($this->_is_notify && $res["status"]==1){
            $message_job=(new MessageJob($this->_user,$this->_template_name,$data))->onQueue("message");
            dispatch($message_job);
        }
    }
}
