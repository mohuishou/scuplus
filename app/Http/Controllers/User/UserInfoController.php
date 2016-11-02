<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use App\Jobs\Jwc\JwcJob;
use App\Jobs\Library\LibraryJob;
use App\Model\User;
use App\Model\UserInfo;
use App\Model\UserLibrary;
use Mohuishou\Lib\Library;
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
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(){
        $res=$this->updateBase($this->_request->user());
        if($res["status"]!=1){
            return $this->error($res["msg"],$res["status"]);
        }
        $user_info_data=$this->_request->user()->userInfo;
        return $this->success('用户信息更新成功',$user_info_data);
    }

    /**
     * @param User $user
     * @return array
     */
    public function updateBase(User $user){
        $jwc=$user->userJwc;
        if(!isset($jwc->verify)||$jwc->verify!=1){
            return [
                "status"=>24011,
                "msg"=>"教务处未绑定，或账号密码错误！",
                "data"=>[]
            ];
        }
        try{
            $user_info=ScuplusJwc::create('Student',$jwc->jwc_id,$jwc->password)->index();
        }catch (\Exception $e){
            return [
                "status"=>"2".$e->getCode(),
                "msg"=>$e->getMessage(),
                "data"=>[]
            ];
        }
        $userinfo_model=UserInfo::firstOrCreate(['uid'=>$user->id]);
        foreach ($user_info as $k => $v){
            if($k=='id'){
                $userinfo_model->id_card=$user_info['id'];
            }else{
                $userinfo_model->$k=$v;
            }
        }
        if($userinfo_model->save()){
            return [
                "status">1,
                "msg"=>"用户信息更新成功！",
                "data"=>[]
            ];
        }
        return [
            "status">25101,
            "msg"=>"数据库错误",
            "data"=>[]
        ];

    }

}