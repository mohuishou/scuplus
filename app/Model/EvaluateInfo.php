<?php
/**
 * Created by mohuishou<1@lailin.xyz>.
 * User: mohuishou<1@lailin.xyz>
 * Date: 2016/8/18 0018
 * Time: 15:16
 */
namespace App\Model;

class EvaluateInfo extends BaseModel
{
    protected $table = 'evaluate_info';

    protected $guarded = [
        'id'
    ];

    public function evaluate(){
        return $this->belongsTo('App\Model\Evaluate', 'eid');
    }

    public function teacher(){
        return $this->hasManyThrough('App\Model\Teacher', 'App\Model\Evaluate','eid','tid');
    }

    public function course(){
        return $this->hasManyThrough('App\Model\Course', 'App\Model\Evaluate','eid','cid');
    }
}