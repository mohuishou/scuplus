<?php
/**
 * Created by PhpStorm.
 * User: lxl
 * Date: 16-10-28
 * Time: 下午3:53
 */

namespace App\Jobs\Message;
use App\Model\Message;
use App\Model\User;
use Curl\Curl;
use Illuminate\Support\Facades\Log;

/**
 * Class SMSJob 消息通知-短信通知队列
 * @package App\Jobs\Message
 */
class SMSJob extends BaseJob
{
    protected $_user;
    protected $_message;
    protected $_args;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user,Message $message,$args)
    {
        $this->_message=$message;
        $this->_user=$user;
        $this->_args=$args;
        if($message->type!=3) {
            Log::warning("消息通知类型错误");
            return;
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        switch ($this->_message->template_id){
            case 10714:
                $msg=$this->_user->username."您好，您的".$this->_message->name."已更新，详情请点击http://scuplus.cn/#!/user 进入用户中心查看【川大加】";
                $this->send($this->_user,$msg);
                break;
            case 10715:
                if(!isset($this->_args["args"][0]["title"])){
                    break;
                }
                $msg=$this->_user->username."您好，您的 ".$this->_args["args"][0]["title"]." 即将超期。详情请点击http://scuplus.cn/#!/user 查看【川大加】";
                echo $msg;
                $this->send($this->_user,$msg);
                break;
        }
    }

    public function send(User $user,$content){
        $appkey=env('SMS_APPKEY');
        $sms_data['appkey']=$appkey;
        $sms_data['mobile']=$user->phone;
        $sms_data['content']=$content;
        $url="http://api.jisuapi.com/sms/send";
        $curl=new Curl();
        $curl->post($url,$sms_data);
        if ($curl->error) {
            Log::debug('短信发送错误！'.'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage);
            return false;
        }
        $result=json_decode($curl->response);
        if($result->status!=0){
            Log::debug('短信发送错误！'.'Error: ' . $result->msg);
            return false;
        }
        return true;
    }
}