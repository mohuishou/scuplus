<?php
/**
 * Created by PhpStorm.
 * User: lxl
 * Date: 16-11-7
 * Time: 下午2:04
 */

namespace App\Console\Commands\Conversion;

use App\Models\Jwc\Course;
use App\Models\Jwc\CourseExtend;
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
        $start=time();
        $courses=Course::all();
        $count=0;
        foreach ($courses as $course){
            $course_extend=CourseExtend::firstOrCreate([
                "cid"=>$course->id
            ]);
            $item=$course->item->first();

            $course_extend->college=$item->college;
            $course_extend->name=$item->name;
            $course_extend->credit=$item->credit;
            $course_extend->exam_type=$item->examType;
            $course_extend->max=$item->max;
            $course_extend->student_number=$item->studentNumber;
            $course_extend->course_limit=$item->courseLimit;

            $course_extend->save();

            $count++;
        }
        $msg="执行完毕，转移数据{$count}条,耗时".(time()-$start)."秒\r\n";
        echo $msg;
        Log::info($msg);
    }

}