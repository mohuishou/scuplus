<?php

namespace App\Http\Middleware;

use App\Model\User;
use Closure;
use Illuminate\Support\Facades\Cache;

class VerifyCodeMiddleware
{
    /**
     * 验证验证码
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!$request->has('verifyCode')){
            return response()->json(['verifyCode'=>'验证码必须!'],422);
        }
        $verify_code=$request->input('verifyCode');
        if(strlen($verify_code)!=6)
            return response()->json(['verifyCode'=>'验证码必须为6位数字'],422);
        $check=Cache::get($verify_code);
        if(!$check)
            return response()->json(['verifyCode'=>'不存在该验证码'],422);
        if($check<0){
            Cache::forget($verify_code);
        }
        return $next($request);
    }
}
