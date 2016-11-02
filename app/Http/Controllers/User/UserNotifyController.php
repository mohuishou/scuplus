<?php
/**
 * Created by PhpStorm.
 * User: lxl
 * Date: 16-11-1
 * Time: 下午6:30
 */

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Model\UserNotify;
use Illuminate\Http\Request;

class UserNotifyController extends Controller{

    /**
     * @var mixed
     */
    protected $_user;

    /**
     * @var
     */
    protected $_notify_model;

    /**
     * UserNotifyController constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
        if ($this->_request->has("param")){
            $this->validate($this->_request, [
                'param' => 'required',
                "status"=>"required|min:0|max:1|numeric"
            ]);
        }
        $this->_user=$this->_request->user();
        if(isset($this->_user->userNotify)&&!empty($this->_user->userNotify)){
            $this->_notify_model=$this->_user->userNotify;
        }else{
            $this->_notify_model=UserNotify::firstOrCreate(["uid"=>$this->_user->id]);
        }

    }

    /**
     * 默认通知方式
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function first(){
        $this->validate($this->_request, [
            'first' => 'required',
        ]);
        $first=$this->_request->input("first");
        $check=in_array($first,["email","weChat","sms"]);
        if(!$check){
            return $this->error("参数错误！");
        }
        $this->_notify_model->first=$first;
        if($this->_notify_model->save()){
            return $this->success("更新成功",$this->_user->userNotify);
        }
        return $this->error("数据库错误");
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function jwc(){
        $param=$this->_request->input("param");
        $check=in_array($param,["grade","schedule","exam"]);
        if (!$check){
            return $this->errorRequest(["param"=>"非法参数"]);
        }
        return $this->update("jwc");
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function library(){
        $param=$this->_request->input("param");
        $check=in_array($param,["now"]);
        if (!$check){
            return $this->errorRequest(["param"=>"非法参数"]);
        }
        return $this->update("library");
    }

    /**
     * @param $pre
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update($pre){
        $param=$pre."_".$this->_request->input("param");
        $status=$this->_request->input("status");
        $this->_notify_model->$param=$status;
        if($this->_notify_model->save()){
            return $this->success("更新成功",$this->_user->userNotify);
        }
        return $this->error("数据库错误");
    }
}