<?php
/**
 * Created by PhpStorm.
 * User: lxl
 * Date: 2016/12/16
 * Time: 20:32
 */

namespace App\Http\Controllers\User;
use App\Models\User\User;
use App\Models\User\UserInfo;
use App\Models\User\UserJwc;
use Illuminate\Http\Request;
use Mohuishou\Lib\ScuplusJwc;

/**
 * 教务处模块基本控制器
 * Class UserJwc
 * @author mohuishou <1@lailin.xyz>
 * @package App\Http\Controllers\User
 */
class UserJwcController extends BaseController{


    public function index(Request $request){
        //字段验证，学号，密码，是否进行登录验证
        $this->validate($request,[
            'student_id'=>'required|min:9|max:15',
            'password'=>'required'
        ],[
            'student_id.required'=>'学号必须！',
            'password.required'=>'密码必须！',
            'student_id.min'=>'学号至少大于9位',
            'student_id.max'=>'学号不能大于15位'
        ]);
        $sid=$request->input('student_id');
        $password=$request->input('password');
        //默认不进行登录验证
        $is_check=0;
        if($request->has('is_check')){
            $is_check=$request->input('is_check');
        }

        //验证用户信息
        $res=$this->show($sid,$password,$is_check);
        return $this->apiReturn($res);
    }

    /**
     * @author mohuishou<1@lailin.xyz>
     * @param $sid
     * @param $password
     * @param int $is_check
     * @return array
     */
    public function show($sid,$password,$is_check=0){
        //查询数据库，是否存在
        $user_jwc_model=UserJwc::where('student_id',$sid)->first();

        //无需登录教务处进行验证
        if(!empty($user_jwc_model)&&$user_jwc_model->verify==1){
            //数据库当中存在,并且账号密码正确，进行判断
            if(!$is_check){
                if($password==decrypt($user_jwc_model->password)){
                    $data['user_jwc_id']=$user_jwc_model->id;
                    $data['token']=$this->createToken(User::find($user_jwc_model->user->id));
                    return $this->successData('验证成功！',$data);
                }else{
                    return $this->errorData('验证失败，学号或密码错误！',24011);
                }
            }
        }

        //登录教务处进行验证
        return $this->loginJwc($sid,$password);
    }


    /**
     * 登录验证，成功返回cookies
     * @author mohuishou<1@lailin.xyz>
     * @param $student_id
     * @param $password
     */
    protected function loginJwc($student_id,$password){
        //登录教务处，如有错误直接捕获，返回
        try{
            $user_info_obj=ScuplusJwc::create('Student',$student_id,$password);
            $user_info=$user_info_obj->index();
        }catch (\Exception $e){
            return $this->errorData($e->getMessage(),$e->getCode());
        }

        //更新用户表
        $res=$this->updateUserJwc($student_id,$password,1);

        //更新用户信息
        $this->updateUserInfo($user_info,$res['user_jwc_id']);

        //返回成功信息
        return $this->successData('登录成功！',$res);
    }

    /**
     * 更新用户
     * @author mohuishou<1@lailin.xyz>
     * @param $student_id
     * @param $password
     * @param int $verify
     */
    protected function updateUserJwc($student_id,$password,$verify=1){
        //添加教务处用户
        $user_jwc_model=UserJwc::firstOrCreate(['student_id'=>$student_id]);
        $user_jwc_model->password=encrypt($password);//保存加密的数据
        $user_jwc_model->verify=$verify;
        $user_jwc_model->save();

        //用户
        $user_model=User::firstOrCreate(['jwc_id'=>$user_jwc_model->id]);
        $user_model->save();

        $data['token']=$this->createToken($user_model);
        $data['user_jwc_id']=$user_jwc_model->id;

        return $data;
    }

    /**
     * 更新用户信息
     * @author mohuishou<1@lailin.xyz>
     * @param $user_info_data
     */
    protected function updateUserInfo($user_info_data,$user_jwc_id){
        $user_info_data['id_card']=$user_info_data['id'];
        $user_info_model=UserInfo::firstOrCreate(['uid'=>$user_jwc_id]);
        $user_info_model->update($user_info_data);
    }
}

