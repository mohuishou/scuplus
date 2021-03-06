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
        return $this->success("考表更新成功",$res["data"]);
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
                $this->verify(0,$user->userJwc);
            }
            $this->_update_return["status"]=$code;
            $this->_update_return["msg"]=$e->getMessage();
            return $this->_update_return;
        }


        if(empty($data)){
            return $this->_update_return;
        }

        //todo:判断即将考试的考试信息,两种情况发送提醒，1:有更新,2:一周以内有考试
        foreach ($data as $k=>$v){
            //判断是否一周内有考试
            $exam_day=strtotime($v["date"]);
            if(($exam_day-time())<(60*60*24*7)&&($exam_day-time())>0){
                $this->_update_return["count"]++;
            }

            //判断是否更新
            $exam_model=Exam::firstOrCreate(["uid"=>$user->id,"class_name"=>$data["class_name"]]);
            if($exam_model->$k!=$v){
                $exam_model->$k=$v;
                if($exam_model->save()) $this->_update_return["count"]++;
            }
        }
        $this->_update_return["data"]=$data;
        return $this->_update_return;
    }
}