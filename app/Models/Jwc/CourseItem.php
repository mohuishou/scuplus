<?php
/**
 * Created by PhpStorm.
 * User: lxl
 * Date: 2016/12/18
 * Time: 21:21
 */

namespace App\Models\Jwc;


use App\Models\BaseModel;

class CourseItem extends BaseModel
{
    protected $table='jwc_course_item';

    public function course(){
        return $this->belongsTo('App\Models\Jwc\Course','cid');
    }

    public function teacher(){
        return $this->belongsToMany('App\Models\Jwc\Teacher','course_teacher','cid','tid');
    }

}