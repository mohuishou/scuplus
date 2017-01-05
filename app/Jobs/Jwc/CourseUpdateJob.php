<?php
/**
 * Created by PhpStorm.
 * User: lxl
 * Date: 2016/12/18
 * Time: 23:51
 */

namespace App\Jobs\Jwc;


use App\Jobs\Job;
use App\Models\Jwc\Course;
use App\Models\Jwc\CourseExtend;
use App\Models\Jwc\CourseItem;
use App\Models\Jwc\CourseTeacher;
use App\Models\Jwc\Teacher;
use Illuminate\Support\Facades\Log;
use Mohuishou\Lib\ScuplusJwc;

class CourseUpdateJob extends Job
{
    protected $_sid;
    protected $_password;
    protected $_page;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($sid,$password,$page)
    {
        $this->_sid=$sid;
        $this->_password=$password;
        $this->_page=$page;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Course $course,CourseExtend $courseExtend,CourseItem $courseItem,Teacher $teacher_model,CourseTeacher $courseTeacher)
    {
        $start=time();
        $course_obj=ScuplusJwc::create('Course',$this->_sid,$this->_password);
        $count=0;
        try{
            $datas=$course_obj->getCourse([],$this->_page,50);
            foreach ($datas as $data){

                //检查是否存在空值
                if(!$this->check($data)){
                    continue;
                }

                //添加基本信息到course表
                $cid=$this->course($data['courseId'],$data['lessonId'],$course);
                if(!$cid){
                    throw new \Exception("course id 值不能为空");
                }

                //添加扩展信息到course_extend
                $extend_data=[
                    'name'=>$data['name'],
                    'college'=>$data['college'],
                    'credit'=>$data['credit'],
                    'exam_type'=>$data['examType'],
                    'max'=>$data['max'],
                    'student_number'=>$data['studentNumber'],
                    'course_limit'=>$data['courseLimit']
                ];
                $this->courseExtend($cid,$extend_data,$courseExtend);

                //添加上课信息到course_item
                $item_data=[
                    'all_week'=>$data['allWeek'],
                    'day'=>$data['day'],
                    'session'=>$data['session'],
                    'campus'=>$data['campus'],
                    'building'=>$data['building'],
                    'classroom'=>$data['classroom']
                ];
                $this->courseItem($cid,$item_data,$courseItem);

                //添加教师信息
                $teachers=explode(',',$data['teacher']);
                foreach ($teachers as $teacher){
                    $tid=$this->teacher($teacher,$data['college'],$teacher_model);
                    //添加到course_teacher
                    $this->courseTeacher($cid,$tid,$courseTeacher);
                }
                $count++;
            }

            $msg="第{$this->_page}页数据抓取成功,耗时".(time()-$start)."秒，成功保存课程{$count}门 \r\n";
            Log::info($msg);
            echo $msg;
        }catch (\Exception $e){
            Log::debug($e->getMessage());
            $this->release(10);
        }
        $course_obj->logout();



    }

    protected function check($data){
        $check_item=['courseId','lessonId','name','college','allWeek','day'];
        foreach ($check_item as $v){
            if(!array_key_exists($v, $data)||empty($data[$v])){
                return false;
            }
        }
        return true;
    }

    protected function course($course_id,$lesson_id,Course $course){
        $course_model=$course->firstOrCreate([
            'course_id'=>$course_id,
            'lesson_id'=>$lesson_id
        ]);
        $course_model->save();
        return $course_model->id;
    }

    protected function courseExtend($cid,$data,CourseExtend $courseExtend){
        $courseExtend->firstOrCreate(['cid'=>$cid]);
        $courseExtend->update($data);
        $courseExtend->save();
        return $courseExtend->id;
    }

    protected function courseItem($cid,$data,CourseItem $courseItem){
        $courseItem->firstOrCreate(['cid'=>$cid]);
        $courseItem->update($data);
        $courseItem->save();
        return $courseItem->id;
    }

    protected function teacher($teacher_name,$college,Teacher $teacher){
        $teacher->firstOrCreate([
            'name'=>$teacher_name,
            'college'=>$college
        ]);
        $teacher->save();
        return $teacher->id;
    }

    protected function courseTeacher($cid,$tid,CourseTeacher $courseTeacher){
        $courseTeacher->firstOrCreate([
            'cid'=>$cid,
            'tid'=>$tid
        ]);
        $courseTeacher->save();
        return $courseTeacher->id;
    }
}