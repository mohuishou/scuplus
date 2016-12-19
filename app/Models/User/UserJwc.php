<?php
/**
 * Created by PhpStorm.
 * User: lxl
 * Date: 2016/12/17
 * Time: 12:59
 */

namespace App\Models\User;


use App\Models\BaseModel;

class UserJwc extends BaseModel
{
    protected $table="user_jwc";

    protected $fillable = ['student_id', 'password'];

    public function userInfo(){
       return $this->hasOne('App\Models\User\UserInfo','uid');
    }

    public function user(){
        return $this->hasOne('App\Models\User\User','jwc_id');
    }

    public function evaluateUpdate(){
        return $this->hasMany('App\Models\Jwc\EvaluateUpdate','user_jwc_id');
    }

}