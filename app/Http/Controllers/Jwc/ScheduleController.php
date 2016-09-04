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

    /**
     * 生成ics文件
     * @author mohuishou<1@lailin.xyz>
     */
    public function ics(){

        //获取课程表数据
        $term=$this->getTerm();
        $data=Schedule::where('term',$term)->get();

        if(empty($data))
            return $this->error(['error'=>'暂时没有课程数据']);

        $ics=<<<EOD
BEGIN:VCALENDAR
PRODID:-//Google Inc//Google Calendar 70.9054//EN
VERSION:2.0
CALSCALE:GREGORIAN
METHOD:PUBLISH
X-WR-CALNAME:SCUPLUS-课表
X-WR-TIMEZONE:Asia/Shanghai
BEGIN:VTIMEZONE
TZID:Asia/Shanghai
X-LIC-LOCATION:Asia/Shanghai
BEGIN:STANDARD
TZOFFSETFROM:+0800
TZOFFSETTO:+0800
TZNAME:CST
DTSTART:19700101T000000
END:STANDARD
END:VTIMEZONE
EOD;

        foreach ($data as $k => &$v){
            $course=$v->course;
            $v->course->teacher;

            //构造教师名字
            $teachers=$course->teacher;
            $teacher="";
            foreach ($teachers as $val){
                $teacher.=$val->name . ",";
            }
            $address=$course->campus ."-". $course->building ."-". $course->classroom;

            $week_start = substr($course -> allWeek, 0, 1 );
            $week_end = substr($course -> allWeek,0,-1);
            $week_arr=explode(',',$course->allWeek);

            //重复周次
            $count='COUNT='.count($week_arr);

            //间隔周次
            ($week_arr[1]-$week_arr[0])!=1 || $interval='INTERVAL=' . $week_arr[1]-$week_arr[0];

            //整理上课节次
            $session_start = substr($course->session, 0, 1 );
            $session_end = substr($course->session,0,-1);
            $attendClassTime=['0815','0910','1015','1110','1350','1445','1550','1645','1740','1920','2015','2110'];
            $finishClassTime=['0900','0955','1100','1155','1435','1530','1635','1730','1825','2005','2100','2155'];

            $school_start=strtotime("2016-09-05");//设置一学期当中的第一天
            $course_start=$school_start+(($course->day)+($week_start-1)*7)*3600*24;
            $course_start=date('Ymd',$course_start);
            //按照ics时间整理
            $dtstart=$course_start.'T'.$attendClassTime[$session_start-1].'00';
            $dtend=$course_start.'T'.$finishClassTime[$session_end-1].'00';

            $uid=md5($this->_user->id)."@scuplus.cn";

            $description="课程号：".$course->courseId . "课序号：".$course->lessonId . " \r\n 学分： ". $course->credit . "周次： ". $week_start ."-".$week_end ."\r\n 平均成绩：" . $course->avg_grade ." 平均得分：" . $course->avg_star ;
            $course->pass_rate || $fail_rate="无";
            $fail_rate=(1-$course->pass_rate)*100;
            $description .= "挂科率：". $fail_rate ."%";


        }




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
        $data=$this->_jwc_obj->notFull();

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