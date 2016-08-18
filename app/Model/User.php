<?php

namespace App\Model;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class User extends BaseModel implements
    AuthenticatableContract,
    AuthorizableContract
{
    use Authenticatable, Authorizable;

    protected $table='user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public function userinfo(){
        return $this->hasOne('App\Model\UserInfo','uid');
    }

    public function evalute(){
        return $this->hasMany('App\Model\Evalute','uid');
    }

    public function schedule(){
        return $this->hasMany('App\Model\Schedule','uid');
    }

    public function grade(){
        return $this->hasMany('App\Model\Grade','uid');
    }
}
