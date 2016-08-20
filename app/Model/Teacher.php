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

    public function course(){
        return $this->belongsToMany('App\Model\Course', 'course_teacher', 'cid', 'tid');
    }

    public function evalute(){
        return $this->hasMany('App\Model\Evalute', 'tid');
    }


}