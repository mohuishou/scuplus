<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use App\Model\UserInfo;
use Mohuishou\Lib\ScuplusJwc;

class UserInfoController extends Controller
{

    /**
     * 获取用户信息
     * @author mohuishou<1@lailin.xyz>
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(){
        $user=$this->_request->user();
        $user->userinfo;
        if($user){
            return $this->success('用户信息获取成功！',$user);
        }
        return $this->error(['error'=>'用户信息获取失败']);
    }

    /**
     * 绑定教务处，并且更新个人信息
     * @author mohuishou<1@lailin.xyz>
     * @return \Laravel\Lumen\Http\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function bindJwc(){
        $this->validate($this->_request, [
            'sid' => 'required|min:10',
            'spassword' => 'required|min:6|max:20',
        ]);

        return $this->success($this->_request->user());

        $sid=$this->_request->input('sid');
        $spassword=$this->_request->input('spassword');
        try{
            $user_info=ScuplusJwc::create('Student',$sid,$spassword)->index();
        }catch (\Exception $e){
            return $this->errorRequest(['error'=>$e->getMessage()]);
        }


        if($user_info){
            $this->_request->user()->sid=$sid;
            $this->_request->user()->spassword=encrypt($spassword);
            $this->_request->user()->save();
            $userinfo_model=UserInfo::firstOrCreate(['uid'=>$this->_request->user()->id]);
            foreach ($user_info as $k => $v){
                if($k=='id'){
                    $userinfo_model->id_card=$user_info['id'];
                }else{
                    $userinfo_model->$k=$v;
                }
            }
            if($userinfo_model->save()){
                return $this->success('教务处绑定成功！，用户信息更新成功！');
            };
        }


    }


}