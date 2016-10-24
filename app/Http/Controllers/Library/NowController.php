<?php
/**
 * Created by PhpStorm.
 * User: lxl
 * Date: 16-10-24
 * Time: 下午3:46
 */

namespace App\Http\Controllers\Library;
use App\Http\Controllers\Controller;
use App\Model\LibraryNow;
use Mohuishou\Lib\Library;
/**
 * 当前借阅信息
 * Class NowController
 * @package App\Http\Controllers\Library
 */
class NowController extends  Controller
{
    /**
     * 当前借阅历史
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        return $this->success("当前借阅信息获取成功！",$this->_request->user()->libraryNow);
    }

    public function update()
    {
        $id=$this->_request->user()->userLibrary->library_id;
        $password=decrypt($this->_request->user()->userLibrary->library_password);
        try{
            $library=new Library($id,$password);
            $data=$library->loanNow();
        }catch (\Exception $e){
            return $this->error($e->getMessage());
        }

        $library_now_model=new LibraryNow();
        $uid=$this->_request->user()->id;
        $library_now_model->where("uid",$uid)->delete();

        $count=0;
        foreach ($data as $k=>$v){
            $v["uid"]=$uid;
            $library_now_model->create($v);
            $count+=$library_now_model->save();
        }
        return $this->success("当前借阅信息更新成功！成功更新 $count 条",$this->_request->user()->libraryNow);

    }
}