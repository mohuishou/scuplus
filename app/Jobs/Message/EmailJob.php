<?php
/**
 * Created by PhpStorm.
 * User: lxl
 * Date: 16-10-28
 * Time: 下午3:52
 */

namespace App\Jobs\Message;
use App\Model\Message;
use App\Model\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

/**
 * Class EmailJob 消息通知-邮件通知队列
 * @package App\Jobs\Message
 */
class EmailJob extends BaseJob
{
    protected $_user;
    protected $_message;
    protected $_args;


    /**
     * EmailJob constructor.
     * @param User $user
     * @param Message $message
     */
    public function __construct(User $user,Message $message,$args)
    {
        $this->_message=$message;
        $this->_user=$user;
        $this->_args=$args;
        if($message->type!=1) {
            Log::warning("消息通知类型错误");
            return;
        }
    }

    /**
     * @param Mail $mail
     */
    public function handle(Mail $mail)
    {
            $mail::send("message.".$this->_message->template_name,$this->_args,function ($m){
                $m->from("admin@scuplus.cn","SCUPLUS");
                $m->to($this->_user->email)->subject('【SCUPLUS】'.$this->_message->name);
            });

    }


}