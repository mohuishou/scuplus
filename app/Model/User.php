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
        'password','spassword'
    ];

    /**
     * 用户信息
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function userinfo(){
        return $this->hasOne('App\Model\UserInfo','uid');
    }

    /**
     * 日历ics文件
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function ics(){
        return $this->hasOne('App\Model\Ics','uid');
    }

    /**
     * 评教
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function evaluate(){
        return $this->hasMany('App\Model\Evaluate','uid');
    }

    /**
     * 课程表
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function schedule(){
        return $this->hasMany('App\Model\Schedule','uid');
    }

    /**
     * 成绩/绩点
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function grade(){
        return $this->hasMany('App\Model\Grade','uid');
    }

    /**
     * 考表相关
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function exam(){
        return $this->hasMany('App\Model\Exam','uid');
    }

    /**
     * 图书馆历史借阅
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function libraryHistory(){
        return $this->hasMany('App\Model\LibraryHistory','uid');
    }

    /**
     * 图书馆当前借阅
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function libraryNow(){
        return $this->hasMany('App\Model\LibraryNow','uid');
    }

    /**
     * 图书馆账号绑定
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userLibrary(){
        return $this->hasOne('App\Model\UserLibrary','uid');
    }

    /**
     * 图书馆账号绑定
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userJwc(){
        return $this->hasOne('App\Model\UserJwc','uid');
    }

    /**
     * 用户提醒
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function userNotify(){
        return $this->hasOne('App\Model\UserNotify','uid');
    }
}
