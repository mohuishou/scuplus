<?php
/**
 * Created by PhpStorm.
 * User: lxl
 * Date: 16-10-28
 * Time: 下午3:51
 */

namespace App\Jobs\Message;
use App\Model\Message;
use App\Model\User;
/**
 * Class MessageJob 消息通知队列
 * @package App\Jobs\Message
 */
class MessageJob extends BaseJob
{
    protected $_user;
    protected $_args;
    protected $_template_name;//grade等等
    protected $_order=[
        "email"=>"1",
        "sms"=>"2",
        "weChat"=>"3"
    ];

    /**
     * MessageJob constructor.
     * @param User $user
     * @param $message_type
     * @param $args
     * @param int $first
     */
    public function __construct(User $user,$template_name,$args,$first="email")
    {
        $this->_user=$user;
        $this->_args=$args;
        $this->_template_name=$template_name;

        //消息通知优先级
        //todo:检测通知优先级
        if(isset($this->_user->userNotify->first)) $first=$this->_user->userNotify->first;
        $this->_order[$first]=0;
        $this->_order=array_flip($this->_order);
        ksort($this->_order);

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        echo "消息队列开始运行 \r\n";
        foreach ($this->_order as $fun){
            if($this->$fun()){
                break;
            }
        }
    }

    //邮件通知
    protected function email(){
        //邮箱是否绑定
        if(!$this->_user->email)
            return false;
        $message_model=Message::where("type",1)->where("template_name",$this->_template_name)->first();
        dispatch(new EmailJob($this->_user,$message_model,$this->_args));
        return true;
    }

    //微信通知
    protected function weChat(){
        echo "微信任务！";
        return false;
    }

    //短信通知
    protected function sms(){
        echo "短信任务！";
        return false;
    }
}