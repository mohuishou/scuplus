<?php
/**
 * Created by mohuishou.
 * User: lxl
 * Date: 16-10-21
 * Time: 下午4:31
 */
namespace App\Http\Controllers\Jwc;
use App\Model\Exam;

class ExamController extends JwcBaseController{
    protected $_jwc_name="Exam";

    public function index(){
        return $this->success("考表获取成功！",$this->_user->exam);
    }

    /**
     * 从教务处获取考试信息
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(){
        //获取当前所有课程
        try{
            $data=$this->_jwc_obj->index();
        }catch (\Exception $e){
            $code=20000;
            if($e->getCode()){
                $code="2".$e->getCode();
            }
            return $this->error("教务处账号密码错误！",$code);
        }

        if(empty($data)){
            return $this->success("更新成功，没有考试信息");
        }

        $exam_model=Exam::firstOrCreate(["uid"=>$this->_user>id,"class_name"=>$data->class_name]);
        $i=0;
        foreach ($data as $k=>$v){
            if($exam_model->$k!=$v){
                $exam_model->$k=$v;
                $i++;
            }
        }

        if($exam_model->save()){
            return $this->success("考表更新成功！新增 {$i} 门课程考试信息！",$data);
        }
        return $this->error("考表更新失败，数据库错误！");
    }
}