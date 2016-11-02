<?php
/**
 * Created by PhpStorm.
 * User: lxl
 * Date: 16-10-24
 * Time: 下午4:47
 */

namespace App\Model;


class UserJwc extends BaseModel
{
    protected $table = 'user_jwc';

    protected $guarded = [
        'id'
    ];


    public function user(){
        return $this->belongsTo('App\Model\User', 'uid');
    }
}