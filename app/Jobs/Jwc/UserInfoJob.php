<?php

namespace App\Jobs\Jwc;

use App\Http\Controllers\User\UserInfoController;
use App\Model\User;

/**
 * Class UserInfoJob
 * @package App\Jobs\Jwc
 */
class UserInfoJob extends BaseJob
{
    public function __construct(User $user, $is_notify=false)
    {
        parent::__construct($user, $is_notify);

    }

    /**
     * @param UserInfoController $user
     */
    public function handle(UserInfoController $user)
    {
        $res=$user->updateBase($this->_user);
    }
}
