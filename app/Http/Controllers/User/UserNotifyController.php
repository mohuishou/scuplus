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

    protected $_user;

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->validate($this->_request, [
            'param' => 'required',
            "status"=>"required|min:0|max:1|numeric"
        ]);
        $this->_user=$this->_request->user();
    }

    public function index(){

    }

    public function jwc(){
        $param="jwc_".$this->_request->input("param");
        $status=$this->_request->input("status");
        UserNotify::firstOrCreate(["uid"=>$this->_user->id]);
    }

    public function library(){
        $param="jwc_".$this->_request->input("param");
        $status=$this->_request->input("status");
    }
}