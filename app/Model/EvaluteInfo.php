<?php
/**
 * Created by mohuishou<1@lailin.xyz>.
 * User: mohuishou<1@lailin.xyz>
 * Date: 2016/8/18 0018
 * Time: 15:16
 */
namespace App\Model;

class EvaluteInfo extends BaseModel
{
    protected $table = 'evalute_info';

    protected $guarded = [
        'id'
    ];

    public function evalute(){
        return $this->belongsTo('App\Model\Evalute', 'eid');
    }
}