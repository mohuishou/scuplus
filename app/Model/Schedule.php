<?php
/**
 * Created by mohuishou<1@lailin.xyz>.
 * User: mohuishou<1@lailin.xyz>
 * Date: 2016/8/18 0018
 * Time: 15:17
 */
namespace App\Model;

class Schedule extends BaseModel
{
    protected $table = 'schedule';

    protected $guarded = [
        'id'
    ];

    public function course(){
        return $this->belongsTo('App\Model\Course', 'cid');
    }

    public function user(){
        return $this->belongsTo('App\Model\User', 'uid');
    }


}