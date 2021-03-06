<?php
/**
 * Created by mohuishou<1@lailin.xyz>.
 * User: mohuishou<1@lailin.xyz>
 * Date: 2016/8/19 0019
 * Time: 10:30
 */
namespace App\Http\Controllers\Jwc;
use App\Http\Controllers\Controller;
use App\Model\Course;
use App\Model\Evaluate;
use App\Model\EvaluateInfo;
use App\Model\Teacher;

class EvaluateController extends Controller{

    /**
     * 新增评教
     * @author mohuishou<1@lailin.xyz>
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function store(){
        $this->validate($this->_request, [
            'cid' => 'required|numeric',
            'tid' => 'required|numeric',
            'stars' => 'required|numeric|min:0|max:5',
            'message' => 'required|max:200|min:6',
        ]);

        $eva_data['cid']=$this->_request->input('cid');
        $eva_data['tid']=$this->_request->input('tid');
        $eva_data['uid']=$this->_request->user()->id;

        //计算该课程的评教平均分
        $course=Course::find($eva_data['cid']);
        $course->updateAvgStar($this->_request->input('stars'));
        //计算该老师的评教平均分
        $teacher=Teacher::find($eva_data['tid']);
        $teacher->updateAvgStar($this->_request->input('stars'));

        //查询是否已经存在该课程该老师的评价
        $evaluate_data=$this->_request->user()->evaluate;
        $eid=0;
        foreach ($evaluate_data as $v){
            if($v['tid']==$eva_data['cid']&&$eva_data['tid']==$v['tid']){
                $eid=$v['id'];
            }
        }

        //不存在，则新建
        if(!$eid){
            $eva=Evaluate::create($eva_data);
            $eid=$eva->id;
        }

        //保存评教的详细信息
        $eva_info_data['stars']=$this->_request->input('stars');
        $eva_info_data['message']=$this->_request->input('message');
        $eva_info_data['eid']=$eid;
        $res=EvaluateInfo::create($eva_info_data);

        if($res)
            return $this->success('评教成功！');
    }


    /**
     * 获取评教信息
     * @author mohuishou<1@lailin.xyz>
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(){
        $map=[];
        if($this->_request->has('cid'))
            $map['cid']=$this->_request->input('cid');
        if($this->_request->has('tid'))
            $map['tid']=$this->_request->input('tid');
        $eva_data=$this->_request->user()->evaluate()->where($map)->paginate(10);
        foreach ($eva_data as $v){
            $v->evaluateInfo;
            $v->course;
            $v->teacher;
        }
        return $this->success('获取成功！',$eva_data);
    }

    public function getAll(){
        $map=[];
        if($this->_request->has('cid'))
            $map['cid']=$this->_request->input('cid');
        if($this->_request->has('tid'))
            $map['tid']=$this->_request->input('tid');
        if(empty($map)){
            return $this->errorRequest("参数错误，cid，tid至少需要一个！");
        }
        $eva_data=Evaluate::where($map)->paginate(10);
        foreach ($eva_data as $v){
            $v->evaluateInfo;
            $v->course;
            $v->teacher;
        }
        return $this->success('获取成功！',$eva_data);
    }


    /**
     * 更新评教信息，评分不能修改
     * @author mohuishou<1@lailin.xyz>
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(){
        $this->validate($this->_request, [
            'id'=>'required|numeric',   //evaluate_info的id
            'message' => 'required|max:200|min:6',
        ]);

        $eva_info=EvaluateInfo::find($this->_request->input('id'));
        $eva_info_data['message']=$this->_request->input('message');
        $res=$eva_info->update($eva_info_data);
        if($res)
            return $this->success('评教更新成功！');
        return $this->error(['error'=>'评教更新失败']);

    }
}