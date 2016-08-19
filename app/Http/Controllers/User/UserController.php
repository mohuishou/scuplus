<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{

    /**
     * 用户类型：邮箱、手机、第三方登录
     * @var array
     */
    protected $_user_type=[
        '1'=>'Email',
        '2'=>'Phone'
    ];

    /**
     * UserController constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->_request=$request;
    }

    /**
     * @author mohuishou<1@lailin.xyz>
     * @param $type
     * @return \Laravel\Lumen\Http\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function register($type){
        if(!is_numeric($type))
            return $this->errorRequest(['type'=>'注册类型错误，type必须为数字']);

        $user_type=$this->userType($type);

        if($user_type){
            return $user_type->register();
        }else{
            return $this->errorRequest(['type'=>'不存在该类型']);
        }
    }

    /**
     * 创建对应用户类型类
     * @author mohuishou<1@lailin.xyz>
     * @param $type
     * @return bool
     */
    protected function userType($type) {
        if(!isset($this->_user_type[$type])){
            return false;
        }
        $classname='\App\Http\Controllers\User\User'.$this->_user_type[$type].'Controller';
        if(class_exists($classname)){
            return new $classname($this->_request);
        }else{
            return false;
        };
    }

    /**
     * 验证，验证邮箱或者手机号，或者其他第三方平台
     * @author mohuishou<1@lailin.xyz>
     * @param $type
     * @param $verify_code
     * @return \Laravel\Lumen\Http\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function verify($type,$verify_code){
        if(!is_numeric($type))
            return $this->errorRequest(['type'=>'注册类型错误，type必须为数字']);

        if(strlen($verify_code)!=32)
            return $this->errorRequest(['verify_code'=>'验证码参数错误！']);

        $user_type=$this->userType($type);

        if($user_type){
            $res=$user_type->verify($verify_code);
            if($res){
                redirect('http"//scuplus.cn/login.html');
            }else{
                return $this->errorRequest(['verify_code'=>'验证码错误或已经失效！']);
            }
        }else{
            return $this->errorRequest(['type'=>'不存在该类型']);
        }
    }


    /**
     * 用户登录
     * @author mohuishou<1@lailin.xyz>
     * @param $type
     * @return \Laravel\Lumen\Http\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function login($type){
        if(!is_numeric($type))
            return $this->errorRequest(['type'=>'注册类型错误，type必须为数字']);

        $user_type=$this->userType($type);

        if($user_type){
            return $user_type->login();
        }else{
            return $this->errorRequest(['type'=>'不存在该类型']);
        }
    }
}
