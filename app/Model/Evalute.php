<?php
/**
 * Created by mohuishou<1@lailin.xyz>.
 * User: mohuishou<1@lailin.xyz>
 * Date: 2016/8/18 0018
 * Time: 15:12
 */
namespace App\Model;

class Evalute extends BaseModel
{
    protected $table = 'evalute';

    protected $guarded = [
        'id'
    ];

    public function course(){
        return $this->belongsTo('App\Model\Course', 'cid');
    }

    public function teacher(){
        return $this->belongsTo('App\Model\Teacher', 'tid');
    }

    public function evaluteInfo(){
        return $this->hasMany('App\Model\EvaluteInfo', 'eid');
    }

    public function user(){
        return $this->belongsTo('App\Model\User', 'uid');
    }

}
