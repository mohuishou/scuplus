<?php
/**
 * Created by PhpStorm.
 * User: lxl
 * Date: 2016/12/18
 * Time: 21:21
 */

namespace App\Models\Jwc;


use App\Models\BaseModel;

class Course extends BaseModel
{
    protected $table='jwc_course';

    public function teacher(){
        return $this->belongsToMany('App\Models\Jwc\Teacher','jwc_course_teacher','cid','tid');
    }

    public function item(){
        return $this->hasMany('App\Models\Jwc\CourseItem','cid');
    }

    public function extend(){
        return $this->hasOne('App\Models\Jwc\CourseExtend','cid');
    }
}