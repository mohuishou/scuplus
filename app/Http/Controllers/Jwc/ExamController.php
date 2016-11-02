<?php
/**
 * Created by mohuishou.
 * User: lxl
 * Date: 16-10-21
 * Time: 下午4:31
 */
namespace App\Http\Controllers\Jwc;
use App\Model\Exam;
use App\Model\User;

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
        $res=$this->updateBase($this->_user);
        if(!$res["status"]!=1){
            return $this->error($res["msg"]);
        }
        return $this->success("考表更新成功，更新".$res["count"]." 条考试信息",$res["data"]);
    }

    public function updateBase(User $user)
    {
        parent::updateBase($user);
        //获取当前所有课程
        try{
            $data=$this->_jwc_obj->index();
        }catch (\Exception $e){
            $code=20000;
            if($e->getCode()){
                $code="2".$e->getCode();
            }
            if($code==24011){
                $this->verify();
            }
            $this->_update_return["status"]=$code;
            $this->_update_return["msg"]=$e->getMessage();
            return $this->_update_return;
        }


        if(empty($data)){
            return $this->_update_return;
        }
        foreach ($data as $k=>$v){
            $exam_model=Exam::firstOrCreate(["uid"=>$user>id,"class_name"=>$data->class_name]);
            if($exam_model->$k!=$v){
                $exam_model->$k=$v;
                if($exam_model->save()) $this->_update_return["count"]++;
            }
        }
        $this->_update_return["data"]=$data;
        return $this->_update_return;
    }
}