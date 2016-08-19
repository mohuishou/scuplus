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
     * 创建token
     * @author mohuishou<1@lailin.xyz>
     * @param User $user
     * @return string
     */
    public function creatToken(User $user){
        $token_arr=[
            'uid'=>$user->id,
            'time'=>60*2 //有效时间2小时
        ];
        $token_str=json_encode($token_arr);
        $token=sha1($token_str);
        Cache::put($token,$user->id,60*2);
        return $token;
    }

    /**
     * 刷新token
     * @author mohuishou<1@lailin.xyz>
     * @param $token
     * @return \Laravel\Lumen\Http\ResponseFactory|string|\Symfony\Component\HttpFoundation\Response
     */
    public function refreshToken($token){
        $uid=Cache::pull($token);
        if($uid){
            return $this->creatToken(User::find($uid));
        }
        return false;
    }


}
