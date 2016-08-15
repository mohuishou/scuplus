<?php
namespace App\Http\Controllers\User;
use App\Model\User;
use Illuminate\Support\Facades\Cache;

class UserEmailController extends UserBaseController
{


    public function login(){

    }

    public function register()
    {
        $this->validate($this->_request, [
            'username' => 'required|unique:user|max:10',
            'password' => 'required|min:6|max:20',
            'email' => 'required|email',
        ]);
        $user=User::create($this->_request->all());

        $verify_code=md5($user->email);
        Cache::put($verify_code, $user->id, 120);//验证码2小时有效
        $verify_url=route('user.verify',[
            'type'=>1,
            'verify_code'=>$verify_code
        ]);

        //Todo: 发送含有验证码连接的邮件给用户

        return $this->success('注册成功，等待验证邮箱！',['url'=>$verify_url]);
    }


    public function verify($verify_code){
        //从缓存当中取出验证码并且删除
        $uid=Cache::pull($verify_code);
        if($uid){
            $user=User::find($uid);
            $user->email_verify=1;
            //Todo: 生成绑定教务处的链接
            if($user->save()){
                return $this->success('邮件验证成功，绑定教务处（可跳过）');
            }
        };
        return $this->errorRequest('邮件验证码错误！');
    }


}