<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;

abstract class UserBaseController extends Controller
{


    /**
     * 注册
     * @author mohuishou<1@lailin.xyz>
     * @return mixed
     */
    abstract public function register();


    /**
     * 登录
     * @author mohuishou<1@lailin.xyz>
     * @return mixed
     */
    abstract public function login();


}
