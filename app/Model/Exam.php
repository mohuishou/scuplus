<?php
/**
 * Created by PhpStorm.
 * User: lxl
 * Date: 16-10-21
 * Time: 下午5:03
 */
namespace App\Model;
class Exam extends BaseModel{
    protected $table = 'exam';

    protected $guarded = [
        'id'
    ];


    public function user(){
        return $this->belongsTo('App\Model\User', 'uid');
    }
}