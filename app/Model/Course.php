<?php
/**
 * Created by mohuishou<1@lailin.xyz>.
 * User: mohuishou<1@lailin.xyz>
 * Date: 2016/8/18 0018
 * Time: 14:44
 */
namespace App\Model;

class Course extends BaseModel{
    protected $table='course';

    protected $guarded = [
        'id'
    ];

    public function teacher(){
        return $this->belongsToMany('App\Model\Teacher', 'course_teacher', 'cid', 'tid');
    }

    public function evalute(){
        $this->hasMany('App\Model\Evalute', 'cid');
    }

    public function schedule(){
        $this->hasMany('App\Model\Schedule', 'cid');
    }

    public function grade(){
        $this->hasMany('App\Model\Grade', 'cid');
    }
}