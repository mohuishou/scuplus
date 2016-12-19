<?php
/**
 * Created by mohuishou<1@lailin.xyz>.
 * User: mohuishou<1@lailin.xyz>
 * Date: 2016/8/16 0016
 * Time: 20:29
 */
namespace App\Models\User;

use App\Models\BaseModel;

class UserInfo extends BaseModel{
    protected $table='user_info';

    protected $hidden = [
        'id_card',
    ];

    public function userJwc(){
        return $this->belongsTo('App\Models\User\UserJwc','uid');
    }
}