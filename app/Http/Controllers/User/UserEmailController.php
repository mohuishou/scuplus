<?php
namespace App\Http\Controllers\User;
use App\Jobs\EmailJob;
use App\Model\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class UserEmailController extends UserBaseController
{



    public function login(){
        $this->validate($this->_request, [
            'password' => 'required|min:32|max:32',  //只接受md5加密的密码字符串
            'email' => 'required|email',
        ]);
        $user=User::where('email',$this->_request->input('email'))
            ->where('password',sha1($this->_request->input('password')))
            ->first();

        if(!$user->email_verify){
            return $this->error(['error'=>'用户邮箱尚未验证！']);
        }
//        return sha1($this->_request->input('password'));
        if(empty($user)){
            return $this->errorRequest(['error'=>'用户名或密码错误']);
        }

        $token=$this->creatToken($user);
        return $this->success('登陆成功！',['token'=>$token]);
    }

    /**
     * 邮箱注册
     * @author mohuishou<1@lailin.xyz>
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function register()
    {
        $this->validate($this->_request, [
            'username' => 'required|unique:user|max:10',
            'password' => 'required|min:32|max:32',  //只接受md5加密的密码字符串
            'email' => 'required|email|unique:user',
        ]);
        $user=new User();
        $user->email=$this->_request->input('email');
        $user->username=$this->_request->input('username');
        $user->password=sha1($this->_request->input('password'));
        $user->save();

        $verify_code=md5($user->email);
        Cache::put($verify_code, $user->id, 60*24);//验证码24小时有效
        $verify_url=route('user.verify',[
            'type'=>1,
            'verify_code'=>$verify_code
        ]);

        //发送含有验证码连接的邮件给用户
        $this->dispatch(new EmailJob());
        $res=Mail::queue('emails.emailVerify',['verify_url'=>$verify_url],function ($m) use($user){
            $m->to($user->email)->subject('【Scuplus】注册验证');
        });

        if($res){
            return $this->success('注册成功，验证邮件已发送，等待验证邮箱！');
        }

    }


    /**
     * 邮箱验证
     * @author mohuishou<1@lailin.xyz>
     * @param $verify_code
     * @return \Laravel\Lumen\Http\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function verify($verify_code){
        //从缓存当中取出验证码并且删除
        $uid=Cache::pull($verify_code);
        if($uid){
            $user=User::find($uid);
            $user->email_verify=1;
            //Todo: 生成绑定教务处的链接,生成token
            $token=$this->creatToken($user);
            if($user->save()){
                return $token;
            }
        };
        return false;
    }


}