<?php
/**
 * Created by mohuishou<1@lailin.xyz>.
 * User: mohuishou<1@lailin.xyz>
 * Date: 2016/8/18 0018
 * Time: 15:12
 */
namespace App\Model;

class Evaluate extends BaseModel
{
    protected $table = 'evaluate';

    protected $guarded = [
        'id'
    ];

    public function course(){
        return $this->belongsTo('App\Model\Course', 'cid');
    }

    public function teacher(){
        return $this->belongsTo('App\Model\Teacher', 'tid');
    }

    public function evaluateInfo(){
        return $this->hasMany('App\Model\EvaluateInfo', 'eid');
    }

    public function user(){
        return $this->belongsTo('App\Model\User', 'uid');
    }

}
