<?php
/**
 * Created by mohuishou<1@lailin.xyz>.
 * User: mohuishou<1@lailin.xyz>
 * Date: 2016/8/18 0018
 * Time: 15:24
 */
namespace App\Http\Controllers\Jwc;

use App\Jobs\CourseJob;
use App\Model\Course;
use App\Model\Teacher;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * 课程信息
 * Class CourseController
 * @package App\Http\Controllers\Jwc
 */
class CourseController extends JwcBaseController{
    protected $_jwc_name='Course';

    /**
     * 课程查询
     * @author mohuishou<1@lailin.xyz>
     * @return mixed
     */
    public function index(){

        $this->validate($this->_request, [
            'college' => 'string',
            'teacher_name' => 'string',
            'name' => 'string',
            'day' => 'numeric|min:1|max:7',
            'session' => 'numeric|min:1|max:5',
            'order' => 'string'
        ]);

        $map=[];
        //模糊查询的字段
        $map_like=[];
        $session_arr=['1,2','3,4','5,6,7','8,9','10,11,12']; //每一大节对应的小节


        if($this->_request->has('name'))
            $map_like['name']=$this->_request->input('name');

        if($this->_request->has('college'))
            $map['college']=$this->_request->input('college');

        if($this->_request->has('day'))
            $map['day']=$this->_request->input('day');

        //通过课程属于第几大节来查询
        if($this->_request->has('session')){
            $session=$this->_request->input('session');
            $session=$session_arr[$session-1];
            $map_like['session']=$session;

        }

        //排序方式，默认倒序，默认字段avg_star
        $order='avg_star';
        if($this->_request->has('order')){
            $order=$this->_request->input('order');
            if(!in_array($order,['avg_star','avg_grade','count_grade','pass_rate'])){
                $order='avg_star';
            }
        }

        /**
         * 通过教师姓名进行查询时,没有分页
         */
        if($this->_request->has('teacher_name')){
            $teacher_name=$this->_request->input('teacher_name');
            $teacher=Teacher::where('name',$teacher_name)->first();
            $course=$teacher->course()->where($map);
            $course=$this->like($course,$map_like);
            $course=$course->orderBy($order, 'desc')->get();
            $teacher->course=$course;
            return $this->success('数据获取成功！',$teacher);
        }

        /**
         * 没有教师姓名的查询
         */
        $course=Course::where($map);
        $course=$this->like($course,$map_like);
        $course=$course->orderBy($order, 'desc')->paginate(10);
        foreach ($course as $v){
            $v->teacher;
        }
        return $this->success('数据获取成功！',$course);
    }


    /**
     * 教师排名
     * @author mohuishou<1@lailin.xyz>
     */
    public function teacher(){
        $this->validate($this->_request, [
            'college' => 'string',
            'name' => 'string',
            'order' => 'string'
        ]);

        //精确查询的字段
        $map=[];
        //模糊查询的字段
        $map_like=[];
        if($this->_request->has('name'))
            $map['name']=$this->_request->input('name');
        if($this->_request->has('college'))
            $map['college']=$this->_request->input('college');

        //排序方式，默认倒序，默认字段avg_star
        $order='avg_star';
        if($this->_request->has('order')){
            $order=$this->_request->input('order');
            if(!in_array($order,['avg_star','avg_grade','count_grade','pass_rate'])){
                $order='avg_star';
            }
        }

        $teacher=Teacher::where($map);
//        $teacher=$this->like($teacher,$map_like);
        $teacher=$teacher->orderBy($order, 'desc')->paginate(10);

        foreach ($teacher as $k=>$v){
            $v->course;
        }

        return $this->success('数据获取成功！',$teacher);



    }

    /**
     * 获取单个课程信息
     * @return mixed
     */
    public function getOne(){
        $this->validate($this->_request, [
            'cid' => 'required'
        ]);
        $cid=$this->_request->input("cid");
        $course=Course::find($cid);
        if($this->_request->has("tid")){
            $tid=$this->_request->input("tid");
            $teacher=$course->teacher->find($tid);
            $course=$course->toArray();//不转换的话下一步赋值不会成功
            $course['teacher']=$teacher;
        }else{
            $course->teacher;
        }
        return $this->success("获取成功！",$course);


    }

    /**
     * 模糊查询
     * @author mohuishou<1@lailin.xyz>
     * @param obj_model
     * @param $map
     * @return mixed
     */
    public function like($obj_model,$map){
        foreach ($map as $k=> $v){
            $obj_model=$obj_model->where($k,'like','%'.$v.'%');
        }
        return $obj_model;
    }



    /**
     * 抓取课程信息以及教师信息，该操作只对管理员开放
     * @author mohuishou<1@lailin.xyz>
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(){
        $res=$this->dispatch(new CourseJob($this->_user->sid,decrypt($this->_user->spassword)));
        if($res){
            return $this->success('课程信息更新任务已添加到后台队列！');
        }
    }



}