<?php
/**
 * Created by mohuishou<1@lailin.xyz>.
 * User: mohuishou<1@lailin.xyz>
 * Date: 2016/8/18 0018
 * Time: 15:24
 */
namespace App\Http\Controllers\Jwc;


use App\Model\Course;
use App\Model\Schedule;

class ScheduleController extends JwcBaseController{
    protected $_jwc_name='Schedule';

    /**
     * 获取最新的课程表
     * @author mohuishou<1@lailin.xyz>
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(){
        $term=$this->getTerm();
        if($this->_request->has('term')){
            $term=$this->_request->input('term');
        }
        $data=Schedule::where('term',$term)->get();
        foreach ($data as $k => &$v){
            $v->course;
            $v->course->teacher;
        }
        return $this->success('课程信息获取成功',$data);
    }

    public function ics(){

    }

    /**
     * 更新课程表
     * @author mohuishou<1@lailin.xyz>
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(){

        $count=0;

        $term=$this->getTerm();
        //是否存在相同学期课程数据
        $old_schedule_data=$this->_user->schedule()->where('term',$term)->first();
        if($old_schedule_data){
            $this->_user->schedule()->where('term',$term)->delete();
        }

        //获取当前所有课程
        $data=$this->_jwc_obj->index();
        $schedule_data['term']=$term;
        $schedule_data['uid']=$this->_user->id;
        foreach ($data as $k => $v){
            $schedule_data['courseType']=$v['courseType'];
            $schedule_data['studyWay']=$v['studyWay'];
            $schedule_data['chooseType']=$v['chooseType'];

            //从课程信息表当中查找相应课程
            $map['courseId']=$v['courseId'];
            $map['lessonId']=$v['lessonId'];
            $course_data=Course::where($map)->get();
            foreach ($course_data as $val){
                $schedule_data['cid']=$val['id'];
                $res=Schedule::create($schedule_data);
                if($res) $count++;
            }
        }

        //从数据库获取课程表信息
        $data=Schedule::where('term',$term)->get();
        foreach ($data as $k => &$v){
            $v->course;
            $v->course->teacher;
        }


        return $this->success('课程数据更新成功！更新'.$count.'门课程',$data);
    }

    /**
     * 计算当前学期，例如2016.1为2016-2017学年，秋季学期 2015.2 为 2015-2016学年，春季学期
     * @author mohuishou<1@lailin.xyz>
     * @return string
     */
    private function getTerm(){
        $year=date('Y');
        $month=date('m');
        if($month>6){
            $term=$year.'.1';
        }else{
            $term =($year-1).'.2';
        }
        return $term;
    }



}