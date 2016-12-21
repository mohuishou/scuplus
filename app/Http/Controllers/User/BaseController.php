<?php
/**
 * Created by PhpStorm.
 * User: lxl
 * Date: 2016/12/16
 * Time: 20:34
 */

namespace App\Http\Controllers\User;


use App\Http\Controllers\Controller;
use App\Models\User\User;
use Illuminate\Support\Facades\Cache;


/**
 * 用户模块基本控制器
 * Class BaseController
 * @author mohuishou <1@lailin.xyz>
 * @package App\Http\Controllers\User
 */
class BaseController extends Controller
{


    /**
     * 创建token
     * @author mohuishou<1@lailin.xyz>
     * @param User $user
     * @return string
     */
    public function createToken(User $user){
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