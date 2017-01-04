<?php
/**
 * Created by PhpStorm.
 * User: lxl
 * Date: 16-11-7
 * Time: 下午2:04
 */

namespace App\Console\Commands\Conversion;

use App\Models\Jwc\Course;
use App\Models\Jwc\CourseItem;
use App\Models\Jwc\CourseTeacher;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CourseConversion extends Command
{
    /**
     * 控制台命令名称
     *
     * @var string
     */
    protected $signature = 'conversion:course';

    /**
     * 控制台命令描述
     *
     * @var string
     */
    protected $description = '课程表数据迁移';


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
        $msg="执行完毕，转移数据{$count}条 \r\n";
        echo $msg;
        Log::info($msg);
    }

}