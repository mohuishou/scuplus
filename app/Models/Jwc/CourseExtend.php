<?php
/**
 * Created by PhpStorm.
 * User: lxl
 * Date: 2016/12/18
 * Time: 21:21
 */

namespace App\Models\Jwc;


use App\Models\BaseModel;

class CourseExtend extends BaseModel
{
    protected $table='jwc_course_extend';

    public function course(){
        return $this->belongsTo('App\Models\Jwc\Course','cid');
    }

}