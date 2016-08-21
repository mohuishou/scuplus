<?php

namespace App\Providers;

use App\Model\User;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        Auth::viaRequest('api', function ($request) {
            if ($request->input('token')) {
                $token=$request->input('token');
                try{
                    $token_arr=json_decode(decrypt($token));
                    $token_now=Cache::get('user.token.'.$token_arr->uid);
                    if($token==$token_now){
                        return User::find($token_arr->uid);
                    }
                }catch (DecryptException $e){
                }

            }
        });
    }
}
