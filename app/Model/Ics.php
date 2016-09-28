<?php
/**
 * Created by mohuishou<1@lailin.xyz>.
 * User: mohuishou<1@lailin.xyz>
 * Date: 2016/8/16 0016
 * Time: 20:29
 */
namespace App\Model;

class Ics extends BaseModel{
    protected $table='ics';
    protected $guarded = [
        'id'
    ];

    public function user(){
        return $this->belongsTo('App\Model\User','uid');
    }
}