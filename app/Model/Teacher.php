<?php
/**
 * Created by mohuishou<1@lailin.xyz>.
 * User: mohuishou<1@lailin.xyz>
 * Date: 2016/8/18 0018
 * Time: 15:08
 */
namespace App\Model;

class Teacher extends BaseModel
{
    protected $table = 'teacher';

    protected $guarded = [
        'id'
    ];

    public function course(){
        return $this->belongsToMany('App\Model\Course', 'course_teacher', 'cid', 'tid');
    }

    public function evalute(){
        return $this->hasMany('App\Model\Evalute', 'tid');
    }


}