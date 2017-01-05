<?php
/**
 * Created by PhpStorm.
 * User: lxl
 * Date: 16-11-7
 * Time: 下午2:04
 */

namespace App\Console\Commands;

use App\Jobs\Jwc\CourseUpdateJob;
use App\Models\Jwc\Course;
use App\Models\Jwc\CourseExtend;
use App\Models\Jwc\CourseItem;
use App\Models\Jwc\CourseTeacher;
use App\Models\User\UserJwc;
use Illuminate\Console\Command;

class Temp extends Command
{
    /**
     * 控制台命令名称
     *
     * @var string
     */
    protected $signature = 'temp:run ';

    /**
     * 控制台命令描述
     *
     * @var string
     */
    protected $description = '测试';


    /**
     * Scuplus constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 执行控制台命令
     *
     * @return mixed
     */
    public function handle()
    {
        $course_items=CourseItem::all();
        $count=0;
        $start=time();
        foreach ($course_items as $course_item){
            $course_model=Course::firstOrCreate([
                'course_id'=>$course_item->courseId,
                'lesson_id'=>$course_item->lessonId
            ]);
            $course_model->save();
            $course_item->cid=$course_model->id;
            $course_item->save();

            $teachers=$course_item->teacher;
            foreach ($teachers as $teacher){
                $course_teacher_model=CourseTeacher::firstOrCreate([
                    'cid'=>$course_model->id,
                    'tid'=>$teacher->id
                ]);
                $course_teacher_model->save();
            }
            $count++;
        }
        $msg="执行完毕，转移数据{$count}条，耗时：".(time()-$start);
        echo $msg;
    }

}