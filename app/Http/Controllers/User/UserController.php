<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Model\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

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
     * 注册
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
     * 刷新token
     * @author mohuishou<1@lailin.xyz>
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function refreshToken(){
        $token=$this->_request->input('token');
        $user_type=$this->userType(1);
        $res=$user_type->refreshToken($token);
        if($res){
            return $this->success('token更新成功！',['token'=>$token]);
        }else{
            return $this->error(['error'=>'token更新失败']);
        }
    }

    /**
     * 发送验证码
     * @author mohuishou<1@lailin.xyz>
     * @param $type
     * @return \Laravel\Lumen\Http\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function sendVerifyCode($type){
        if(!is_numeric($type))
            return $this->errorRequest(['type'=>'类型错误，type必须为数字']);

        $user_type=$this->userType($type);

        if($user_type){
            return $user_type->sendVerifyCode();
        }else{
            return $this->errorRequest(['type'=>'不存在该类型']);
        }
    }

    /**
     * 修改密码
     * @author mohuishou<1@lailin.xyz>
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updatePassword(){
        $this->validate($this->_request, [
            'password' => 'required|max:32|min:32',
        ]);

        //从缓存当中获取uid
        $uid=Cache::pull($this->_request->input('verifyCode'));
        $res=User::find($uid)->update(['password'=>$this->_request->input('password')]);
        if($res)
            return $this->success('密码修改成功！');
        return $this->error(['error'=>'密码修改失败！']);
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

    /**
     * 用户检测
     * @author mohuishou<1@lailin.xyz>
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function checkUser(){
        $this->validate($this->_request, [
            'param' => 'required', //需要验证的参数名
            'value' => 'required', //验证的值
        ]);
        $check_area=['username','email','phone'];
        $param=$this->_request->input('param');
        $v=$this->_request->input('value');
        $res=in_array($param,$check_area);
        if(!$res)
            return $this->error(['error'=>'只能验证'.implode(',',$check_area).'当中的一个']);
        $res=User::where($param,$v)->first();
        if($res)
            return $this->success('该用户已存在！',['user'=>1]);
        return $this->success('不存在该用户',['user'=>0]);
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
}
