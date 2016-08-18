<?php

namespace App\Jobs;

use App\Model\Course;
use App\Model\CourseTeacher;
use App\Model\Teacher;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Mohuishou\Lib\ScuplusJwc;

class CourseJob extends Job
{

    protected $_sid;
    protected $_spassword;

    /**
     * CourseJob constructor.
     * @param $course_obj 抓取课程信息的对象
     */
    public function __construct($sid,$spassword)
    {
        $this->_sid=$sid;
        $this->_spassword=$spassword;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $page=Cache::pull('course_page');
        $page || $page=0;
        $course_obj=ScuplusJwc::create('Course',$this->_sid,$this->_spassword);
        while (1){
            //把当前的页码缓存，防止队列任务挂掉从头开始
            Cache::put('course_page',$page,60*5);
            try{
                $data=$course_obj->getCourse([],$page,50);
            }catch (\Exception $e){
                Log::debug($e->getMessage());
            }

            // 当没有数据以后，退出抓取,并且清除缓存
            if(!$data){
                Cache::forget('course_page');
                break;
            }

            //增加页码
            $page++;

            //将数据保存到数据库
            $i_course=0;
            $i_teacher=0;
            foreach ($data as $k => $v){
                $teacher_arr=explode(',',$v['teacher']);
                $teacher_data['college']=$v['college'];
                unset($v['teacher']);
                $res_course=Course::firstOrCreate($v);
                if($res_course){
                    $i_course++;
                }
                $course_teacher_data['cid']=$res_course['id'];
                foreach ($teacher_arr as $val){
                    $teacher_data['name']=$val;
                    $res_teacher=Teacher::firstOrCreate($teacher_data);
                    $course_teacher_data['tid']=$res_teacher['id'];
                    $res_course_teacher=CourseTeacher::firstOrCreate($course_teacher_data);
                    if($res_course_teacher){
                        $i_teacher++;
                    }
                }
            }

            Log::info( $i_course.'组课程数据入库成功！  '.$i_teacher.'组教师数据入库成功！');
        }

    }


}
