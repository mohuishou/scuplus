<?php
/**
 * Created by PhpStorm.
 * User: lxl
 * Date: 16-10-28
 * Time: 下午3:51
 */

namespace App\Jobs\Jwc;

use App\Jobs\Job;
use App\Model\User;

class BaseJob extends Job
{

    protected $_user;
    protected $_is_notify;
    protected $_template_name;
    protected $_first;

    /**
     * BaseJob constructor.
     * @param User $user
     * @param bool $is_notify
     * @param string $first
     */
    public function __construct(User $user,$is_notify=false)
    {
        $this->_user=$user;
        $this->_is_notify=$is_notify;
    }
}