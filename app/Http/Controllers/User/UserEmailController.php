<?php
namespace App\Http\Controllers\User;
use App\Jobs\EmailJob;
use App\Model\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class UserEmailController extends UserBaseController
{


    /**
     * 登录
     * @return \Laravel\Lumen\Http\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function login(){
        $this->validate($this->_request, [
            'password' => 'required|min:32|max:32',  //只接受md5加密的密码字符串
            'email' => 'required|email',
        ]);
        $user=User::where('email',$this->_request->input('email'))
            ->where('password',sha1($this->_request->input('password')))
            ->first();

        if(empty($user)){
            return $this->errorRequest(['error'=>'邮箱或密码错误']);
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
        $res=$user->save();
        if($res){
            return $this->success('注册成功！');
        }
    }

    /**
     * 发送验证码
     * @author mohuishou<1@lailin.xyz>
     */
    public function sendVerifyCode()
    {
        $this->validate($this->_request, [
            'email' => 'required|email',
        ]);
        $email=$this->_request->input('email');

        //邮件发送限制，避免被恶意利用对同一邮箱进行轰炸
        if(Cache::get('verify.'.$email)){
            return $this->error(['error'=>'两分钟内已发送验证码邮件，请两分钟之后再试']);
        }
        if(Cache::get('verify.count.'.$email)>=10){
            return $this->error(['error'=>'8小时以内已经向'.$email.'发送邮件超过10封，请之后再试']);
        }

        //是否需要验证用户是否存在
        $check=0;
        if($this->_request->has('check')){
            $check=$this->_request->input('check');
        }
        if($check==1){
            $user=User::where('email',$email)->first();
            if(!$user)
                return $this->errorRequest(['email'=>'该用户不存在！']);
            $uid=$user->id;
        }

        if(isset($uid)){
            $verify_code=$this->createVerifyCode($uid);
        }else{
            $verify_code=$this->createVerifyCode();
        }
        //发送含有验证码连接的邮件给用户
        $this->dispatch(new EmailJob());
        $res=Mail::queueOn('email','emails.verifyCode',[
            'verify_code'=>$verify_code,
        ],function ($m) use($email){
            $m->to($email)->subject('【Scuplus】验证码');
        });

        if($res){
            Cache::put('verify.'.$email,'1',2);//两分钟内不允许重复发送

            //统计8小时以内对同一邮箱发送的邮件数目
            if(Cache::get('verify.count.'.$email)){
                Cache::increment('verify.count.'.$email, 1);
            }else{
                Cache::put('verify.count.'.$email,'1',60*8);
            }

            return $this->success('验证码邮件已发送，等待查收！');
        }
    }

    /**
     * 绑定邮箱
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function bind(){
        $this->validate($this->_request, [
            'email' => 'required|email|unique:user',
        ]);
        $email=$this->_request->input('email');
        $user=$this->_request->user();
        $user->email=$email;
        if($user->save()){
            return $this->success("邮箱".$email."绑定成功",$email);
        }
        return $this->error("邮箱".$email."绑定失败！");
    }




}