<?php

namespace App\Http\Middleware;

use Closure;

class JwcVerifyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $jwc=$request->user()->userJwc;
        if(!isset($jwc->verify) || $jwc->verify!=1){
            return response()->json(['error'=>'教务处认证失败！',"code"=>24011],401);
        }
        return $next($request);
    }
}
