<?php

namespace App\Http\Middleware;

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
        $check=Cache::pull($verify_code);
        if(!$check)
            return response()->json(['verifyCode'=>'不存在该验证码'],422);
        return $next($request);
    }
}
