<?php
/**
 * Created by mohuishou<1@lailin.xyz>.
 * User: mohuishou<1@lailin.xyz>
 * Date: 2016/8/16 0016
 * Time: 20:29
 */
namespace App\Model;

class UserInfo extends BaseModel{
    protected $table='user_info';
    protected $guarded = [
        'id'
    ];
    protected $hidden = [
        'id_card',
    ];
    public function user(){
        return $this->belongsTo('App\Model\User','uid');
    }
}