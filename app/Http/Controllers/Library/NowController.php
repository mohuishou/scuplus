<?php
/**
 * Created by PhpStorm.
 * User: lxl
 * Date: 16-10-24
 * Time: 下午3:46
 */

namespace App\Http\Controllers\Library;
use App\Model\LibraryNow;
/**
 * 当前借阅信息
 * Class NowController
 * @package App\Http\Controllers\Library
 */
class NowController extends  LibraryBaseController
{
    /**
     * 当前借阅历史
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        return $this->success("当前借阅信息获取成功！",$this->_user->libraryNow);
    }

    public function update()
    {
        try{
            $data=$this->_library->loanNow();
        }catch (\Exception $e){
            return $this->error($e->getMessage());
        }

        $library_now_model=new LibraryNow();
        $uid=$this->_user->id;
        $library_now_model->where("uid",$uid)->delete();

        $count=0;
        foreach ($data as $k=>$v){
            $v["uid"]=$uid;
            $library_now_model->create($v);
            $count+=$library_now_model->save();
        }
        return $this->success("当前借阅信息更新成功！成功更新 $count 条",$this->_user->libraryNow);

    }
}