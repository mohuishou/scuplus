<?php
/**
 * Created by PhpStorm.
 * User: lxl
 * Date: 16-10-24
 * Time: 下午3:46
 */

namespace App\Http\Controllers\Library;
use App\Model\LibraryNow;
use App\Model\User;

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
        $res=$this->updateBase($this->_user);
        if($res["status"]!=1){
            return $this->error($res["msg"]);
        }
        return $this->success("当前借阅信息更新成功！",$this->_user->libraryNow);

    }

    public function updateBase(User $user)
    {
        parent::updateBase($user);
        try{
            $data=$this->_library->loanNow();
        }catch (\Exception $e){
            return $this->error($e->getMessage());
        }

        $library_now_model=new LibraryNow();
        $uid=$this->_user->id;
        //判断是否更新
//        $library_now_model->where("uid",$uid)->delete();

        foreach ($data as $k=>$v){
            //判断是否即将超期,如果有即将超期的书籍，记录
            $v["uid"]=$uid;
            $end=strtotime($v["end_day"]);
            $rest=(time()-$end)/(60*60*24);
            if($rest<5){
                $this->_update_return["count"]++;
                $this->_update_return["data"][]=$v;
            }
//            $library_now_model->create($v);
            $library_now_model=$library_now_model->firstOrCreate([
                "uid"=>$uid,
                "review_id"=>$v["review_id"],
            ]);
            foreach ($v as $key => $value){
                $library_now_model->$key=$value;
            }
            $library_now_model->save();
        }
        return $this->_update_return;
    }
}