<?php
/**
 * Created by PhpStorm.
 * User: lxl
 * Date: 16-10-24
 * Time: 下午3:47
 */

namespace App\Http\Controllers\Library;

/**
 * 图书借阅
 * Class LoanController
 * @package App\Http\Controllers\Library
 */
class LoanController
{

    /**
     * 获取借阅历史
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        return $this->success("借阅历史获取成功！",$this->_request->user()->libraryHistory);
    }

    public function update()
    {
        $sid=$this->_request->user()->sid;
        $spassword=decrypt($this->_request->user()->spassword);
        try{
            $library=new Library($sid,$spassword);
            $data=$library->loanNow();
        }catch (\Exception $e){
            return $this->error($e->getMessage());
        }

        $library_history=new LibraryHistory();

        foreach ($data as $k=>$v){

        }


    }
}