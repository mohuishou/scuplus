<?php
/**
 * Created by PhpStorm.
 * User: lxl
 * Date: 16-10-24
 * Time: 下午3:15
 */
namespace App\Http\Controllers\Library;
use App\Model\LibraryHistory;
use App\Model\User;

/**
 * 借阅历史信息
 * Class HistoryController
 * @package App\Http\Controllers\Library
 */
class HistoryController extends LibraryBaseController{

    /**
     * 获取借阅历史
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        return $this->success("借阅历史获取成功！",$this->_user->libraryHistory);
    }

    /**
     * 更新借阅历史
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update()
    {
        $res=$this->updateBase($this->_user);
        if($res["status"]!=1){
            return $this->error($res["msg"]);
        }
        return $this->success("历史借阅信息更新成功！成功更新 {$res['count']} 条",$this->_user->libraryHistory);
    }

    /**
     * @param User $user
     * @return array
     */
    public function updateBase(User $user)
    {
        parent::updateBase($user);
        try{
            $data=$this->_library->loanHistory();
        }catch (\Exception $e){
            $this->_update_return["status"]=0;
            $this->_update_return["msg"]=$e->getMessage();
            return $this->_update_return;
        }

        $library_history_model=new LibraryHistory();
        $uid=$this->_user->id;
        $library_history_data=$library_history_model->where("uid",$uid)->first();

        foreach ($data as $k=>$v){
            if($library_history_data["end_time"]==$v["end_time"]){
                break;
            }
            $v["uid"]=$uid;
            $lib=$library_history_model->create($v);
            $this->_update_return["count"]+=$lib->save();
        }
        $this->_update_return["data"]=$user->libraryHistory();
        return $this->_update_return;
    }
}