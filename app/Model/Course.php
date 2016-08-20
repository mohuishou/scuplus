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


    /**
     * 更新平均成绩
     * @author mohuishou<1@lailin.xyz>
     * @param $grade
     * @return bool|int
     */
    public function updateAvgGrade($grade){
        $data['count_grade']=$this->count_grade+1;
        $data['avg_grade']=($this->count_grade*$this->avg_grade+$grade)/($data['count_grade']);
        return $this->update($data);
    }

    /**
     * 更新平均评教平均分
     * @author mohuishou<1@lailin.xyz>
     * @param $stars
     * @return bool|int
     */
    public function updateAvgStar($stars){
        $data['count_star']=$this->count_star+1;
        $data['avg_star']=($this->count_star*$this->avg_grade+$stars)/($data['count_star']);
        return $this->update($data);
    }

    public function teacher(){
        return $this->belongsToMany('App\Model\Teacher', 'course_teacher', 'cid', 'tid');
    }

    public function evaluate(){
        return $this->hasMany('App\Model\Evaluate', 'cid');
    }

    public function schedule(){
        return $this->hasMany('App\Model\Schedule', 'cid');
    }

    public function grade(){
        return $this->hasMany('App\Model\Grade', 'cid');
    }
}