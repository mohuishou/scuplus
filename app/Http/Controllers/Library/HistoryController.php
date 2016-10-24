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

    public function __construct()
    {

    }

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
            $data=$library->loanHistory();
        }catch (\Exception $e){
            return $this->error($e->getMessage());
        }

        $library_history=new LibraryHistory();

        foreach ($data as $k=>$v){

        }


    }
}