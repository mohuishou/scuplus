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

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user,$message_type,$args,$first=1)
    {
        $this->_user=$user;
        $this->_args=$args;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

    }
}