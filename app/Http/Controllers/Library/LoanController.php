<?php
/**
 * Created by PhpStorm.
 * User: lxl
 * Date: 16-10-24
 * Time: 下午3:47
 */

namespace App\Http\Controllers\Library;
use App\Http\Controllers\Controller;

/**
 * 图书借阅
 * Class LoanController
 * @package App\Http\Controllers\Library
 */
class LoanController extends LibraryBaseController
{

    /**
     * 续借一本
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function one(){
        $this->validate($this->_request, [
            'review_id' => 'required'
        ]);
        $review_id=$this->_request->input("review_id");
        try{
            $res=$this->_library->loanSome($review_id);
        }catch (\Exception $e){
            $this->error($e->getMessage());
        }
        if($res["result"]){
            return $this->success("续借成功！",$res);
        }
        $res['error']="续借失败！";
        return $this->error($res);

    }

    /**
     * 续借全部
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function all(){
        try{
            $res=$this->_library->loanAll();
        }catch (\Exception $e){
            $this->error($e->getMessage());
        }
        if($res["result"]){
            return $this->success("续借成功！",$res);
        }
        $res['error']="续借失败！";
        return $this->error($res);
    }



}