<?php
/**
 * Created by mohuishou<1@lailin.xyz>.
 * User: mohuishou<1@lailin.xyz>
 * Date: 2016/8/18 0018
 * Time: 15:24
 */
namespace App\Http\Controllers\Jwc;

use App\Jobs\CourseJob;
use App\Model\Teacher;

/**
 * 课程信息
 * Class CourseController
 * @package App\Http\Controllers\Jwc
 */
class CourseController extends JwcBaseController{
    protected $_jwc_name='Course';

    public function index(){
        if($this->_request->has('teacher_name')){
            $teacher_name=$this->_request->input('teacher_name');
            Teacher::where('name',$teacher_name);
        }

        $data=['college','courseId','name','day'];
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