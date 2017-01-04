<?php
/**
 * Created by PhpStorm.
 * User: lxl
 * Date: 2016/12/18
 * Time: 21:21
 */

namespace App\Models\Jwc;


use App\Models\BaseModel;

class Teacher extends BaseModel
{
    protected $table='jwc_teacher';
    public function course(){
        return $this->belongsToMany('App\Models\Jwc\Teacher','jwc_course_teacher','cid','tid');
    }
}