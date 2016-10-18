<?php
/**
 * Created by mohuishou<1@lailin.xyz>.
 * User: mohuishou<1@lailin.xyz>
 * Date: 2016/8/18 0018
 * Time: 15:24
 */
namespace App\Http\Controllers\Jwc;


use App\Model\Course;
use App\Model\Ics;
use App\Model\Schedule;
use Illuminate\Support\Facades\Storage;

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
            $v->course->evaluate;
            $v->course->evaluate->evaluateInfo;
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
            $address="@".$course->campus ." ". $course->building ." ". $course->classroom;


            $week_arr=explode(',',$course->allWeek);
            $week_start =$week_arr[0];
            $week_end = end($week_arr);
            //重复周次
            $count='COUNT='.count($week_arr);
            //间隔周次
            $interval="";
            if(($week_arr[1]-$week_arr[0])!=1){
                $interval=';INTERVAL=' . ($week_arr[1]-$week_arr[0]);
            }

            //整理上课节次
            $session_arr=explode(',',$course->session);
            $session_start =$session_arr[0];
            $session_end = end($session_arr);

            //上下课时间
            $class_time=[
                [
                    "attend"=>['0815','0910','1015','1110','1350','1445','1550','1645','1740','1920','2015','2110'],
                    "finish"=>['0900','0955','1100','1155','1435','1530','1635','1730','1825','2005','2100','2155']
                ],
                [
                    "attend"=>['0800','0855','1000','1055','1400','1455','1550','1655','1750','1930','2025','2120'],
                    "finish"=>['0845','0940','1045','1140','1445','1540','1635','1740','1835','2015','2110','2205']
                ]
            ];


            $school_start=strtotime("2016-09-04");//设置一学期当中的第一天，从周日开始计算
            $course_start=$school_start+(($course->day)+($week_start-1)*7)*3600*24;
            if($week_start==1&& $course->day==7)
                $course_start=$school_start;
            $course_start=date('Ymd',$course_start);

            $campus=$this->_user->userinfo->campus;
            if($campus=="江安"){
                $campus_id=0;
            }else{
                $campus_id=1;
            }

            //按照ics时间整理
            $dtstart=$course_start.'T'.$class_time[$campus_id]["attend"][$session_start-1].'00';
            $dtend=$course_start.'T'.$class_time[$campus_id]["finish"][$session_end-1].'00';

            //整理星期几上课
            $c=['MO','TU','WE','TH','FR','SA','SU'];
            $byday=$c[$course->day-1];

            $uid=md5($this->_user->id)."@scuplus.cn";

            $description="教师：".$teacher."\\n课程号：".$course->courseId . "，课序号：".$course->lessonId . " \\n学分： ". $course->credit . "，周次： ". $week_start ."-".$week_end ."\\n平均成绩：" . $course->avg_grade ." ，平均得分：" . $course->avg_star ;
            if($course->pass_rate){
                $fail_rate=(1-$course->pass_rate)*100;
            }else{
                $fail_rate="无";
            }
            $description .= "，挂科率：". $fail_rate ."%";

            $ics .=<<<EOD

BEGIN:VEVENT
DTSTART;TZID=Asia/Shanghai:$dtstart
DTEND;TZID=Asia/Shanghai:$dtend
RRULE:FREQ=WEEKLY;$count{$interval};BYDAY=$byday
DTSTAMP:{$dtstart}
UID:
CREATED:{$dtstart}
DESCRIPTION:$description
LAST-MODIFIED:{$dtend}Z
LOCATION:$address
SEQUENCE:0
STATUS:CONFIRMED
SUMMARY:{$course->name}-{$v->courseType}
TRANSP:OPAQUE
END:VEVENT
EOD;
        }
        try{
            $ics .="\r\n".'END:VCALENDAR';
            $ics_model=$this->_user->ics;
            $ics_exist=isset($ics_model->file_name);

            //Todo:能不能直接修改文件内容

            if($ics_exist)
                Storage::delete("ics\\".$ics_model->file_name);
            $file_name=md5(time()).".ics";
            //生成文件保存
            $file_res=Storage::put("ics\\".$file_name,$ics);
            if($file_res){
                if($ics_exist){
                    $ics_model->file_name=$file_name;
                    $ics_model->save();
                }else{
                    Ics::create(
                        [
                            'uid'=>$this->_user->id,
                            'file_name'=>$file_name
                        ]
                    );
                }
                $file_link=route("download.ics",['file_name'=>$file_name]);
                return $this->success("ical文件生成成功！",['url'=>$file_link]);
            }else{
                return $this->error(['error'=>"ical文件生成失败，请稍后再试！"]);
            }
        }catch (\ErrorException $e){
            return $this->error($e->getMessage());
        }
    }

    /**
     * 课程表ical文件下载
     * @author mohuishou<1@lailin.xyz>
     * @param $file_name 完整的文件名
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function icsDownload($file_name){
        $path= storage_path("app/ics/".$file_name);
        return response()->download($path, "schedule.ics");
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
        try{
            $data=$this->_jwc_obj->notFull();
        }catch (\Exception $e){
            $code=20000;
            if($e->getCode()){
                $code="2".$e->getCode();
            }
            return $this->error("教务处账号密码错误！",$code);
        }

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