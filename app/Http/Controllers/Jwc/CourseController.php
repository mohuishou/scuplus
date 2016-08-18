<?php
/**
 * Created by mohuishou<1@lailin.xyz>.
 * User: mohuishou<1@lailin.xyz>
 * Date: 2016/8/18 0018
 * Time: 15:24
 */
namespace App\Http\Controllers\Jwc;

use App\Jobs\CourseJob;

class CourseController extends JwcBaseController{
    protected $_jwc_name='Course';

    public function index(){
        $res=$this->dispatch(new CourseJob($this->_request->user()->sid,decrypt($this->_request->user()->spassword)));
        if($res){
            return $this->success('课程信息更新任务已添加到后台队列！');
        }
    }



}