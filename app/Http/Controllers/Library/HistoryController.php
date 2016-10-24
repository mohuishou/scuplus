<?php
/**
 * Created by PhpStorm.
 * User: lxl
 * Date: 16-10-24
 * Time: 下午3:15
 */
namespace App\Http\Controllers\Library;

use App\Http\Controllers\Controller;
use App\Model\LibraryHistory;
use Mohuishou\Lib\Library;

/**
 * 借阅历史信息
 * Class HistoryController
 * @package App\Http\Controllers\Library
 */
class HistoryController extends Controller{

    /**
     * 获取借阅历史
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        return $this->success("借阅历史获取成功！",$this->_request->user()->libraryHistory);
    }

    /**
     * 更新借阅历史
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update()
    {
        $id=$this->_request->user()->userLibrary->library_id;
        $password=decrypt($this->_request->user()->userLibrary->library_password);
        try{
            $library=new Library($id,$password);
            $data=$library->loanHistory();
        }catch (\Exception $e){
            return $this->error($e->getMessage());
        }

        $library_history_model=new LibraryHistory();
        $uid=$this->_request->user()->id;
        $library_history_data=$library_history_model->where("uid",$uid)->first();

        $count=0;
        foreach ($data as $k=>$v){
            if($library_history_data["end_time"]==$v["end_time"]){
                break;
            }
            $v["uid"]=$uid;
            $library_history_model->create($v);
            $count+=$library_history_model->save();
        }
        return $this->success("历史借阅信息更新成功！成功更新 $count 条",$this->_request->user()->libraryHistory);


    }
}