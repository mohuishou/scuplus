<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use App\Model\User;
use Illuminate\Support\Facades\Cache;

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


    /**
     * 发送验证码
     * @author mohuishou<1@lailin.xyz>
     * @return mixed
     */
    abstract public function sendVerifyCode();

    /**
     * 生成6位数字验证码，2小时以内有效
     * @author mohuishou<1@lailin.xyz>
     * @param int $uid
     * @return string
     */
    public function createVerifyCode($uid=-1){
        $verify_code='';
        for ($i=0;$i<6;$i++)
            $verify_code .=rand(0,9);
        Cache::put($verify_code,$uid,60*2);
        return $verify_code;
    }


    /**
     * 创建token
     * @author mohuishou<1@lailin.xyz>
     * @param User $user
     * @return string
     */
    public function creatToken(User $user){
        $token_arr=[
            'uid'=>$user->id,
            'time'=>60*2, //有效时间2小时
            'start'=>time() //token创建时间
        ];
        $token_str=json_encode($token_arr);
        $token=encrypt($token_str);
        Cache::put('user.token.'.$user->id,$token,60*2);
        return $token;
    }

    /**
     * 刷新token
     * @author mohuishou<1@lailin.xyz>
     * @param $token
     * @return \Laravel\Lumen\Http\ResponseFactory|string|\Symfony\Component\HttpFoundation\Response
     */
    public function refreshToken($token){
        $token_arr=json_decode(decrypt($token));
        $token_now=Cache::pull('user.token.'.$token_arr->uid);
        if($token==$token_now){
            return $this->creatToken(User::find($token_arr->uid));
        }
        return false;
    }


}
