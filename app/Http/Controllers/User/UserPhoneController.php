<?php
/**
 * Created by mohuishou<1@lailin.xyz>.
 * User: mohuishou<1@lailin.xyz>
 * Date: 2016/8/22 0022
 * Time: 11:57
 */
namespace App\Http\Controllers\User;
use Curl\Curl;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class UserPhoneController extends UserBaseController{

    public function login()
    {
        $this->validate($this->_request, [
            'password' => 'required|min:32|max:32',  //只接受md5加密的密码字符串
            'phone' => 'required|min:11|max:11|',
        ]);
        $user=User::where('phone',$this->_request->input('phone'))
            ->where('password',sha1($this->_request->input('password')))
            ->first();

        if(empty($user)){
            return $this->errorRequest(['error'=>'手机号或密码错误']);
        }

        $token=$this->creatToken($user);
        return $this->success('登陆成功！',['token'=>$token]);
    }

    public function register()
    {
        $this->validate($this->_request, [
            'username' => 'required|unique:user|max:10',
            'password' => 'required|min:32|max:32',  //只接受md5加密的密码字符串
            'phone' => 'required|min:11|max:11|unique:user',
        ]);
        $user=new User();
        $user->phone=$this->_request->input('phone');
        $user->username=$this->_request->input('username');
        $user->password=sha1($this->_request->input('password'));
        $res=$user->save();
        if($res){
            return $this->success('注册成功！');
        }
    }

    public function sendVerifyCode()
    {
        $this->validate($this->_request, [
            'phone' => 'required|max:11|min:11',
        ]);
        $phone=$this->_request->input('phone');

        //短信发送限制，避免被恶意利用对同一手机号进行轰炸
        if(Cache::get('verify.'.$phone)){
            return $this->error(['error'=>'两分钟内已发送验证码短信，请两分钟之后再试']);
        }
        if(Cache::get('verify.count.'.$phone)>=5){
            return $this->error(['error'=>'12小时以内已经向'.$phone.'发送短信超过5条，请之后再试']);
        }

        //是否需要验证用户是否存在
        $check=0;
        if($this->_request->has('check')){
            $check=$this->_request->input('check');
        }
        if($check==1){
            $user=User::where('phone',$phone)->first();
            if(!$user)
                return $this->errorRequest(['phone'=>'该用户不存在！']);
            $uid=$user->id;
        }

        if(isset($uid)){
            $verify_code=$this->createVerifyCode($uid);
        }else{
            $verify_code=$this->createVerifyCode();
        }

        Cache::put('verify.'.$phone,'1',2);//两分钟内不允许重复发送
        //统计12小时以内对同一手机号发送的短信数目
        if(Cache::get('verify.count.'.$phone)){
            Cache::increment('verify.count.'.$phone, 1);
        }else{
            Cache::put('verify.count.'.$phone,'1',60*12);
        }

        return;

        //发送手机验证码
        $content="尊敬的用户您好：您的验证码为 $verify_code ,验证码2小时内有效，谢谢您的使用【川大加】";
        $res=$this->sendSms($phone,$content);
        if(!$res)
            return $this->error(['error'=>'短信发送失败！']);
        return $this->success('手机验证码已发送，等待查收！');
    }


    /**
     * 发送短信
     * @author mohuishou<1@lailin.xyz>
     * @param $phone 手机号
     * @param $content 内容
     * @return bool
     */
    protected function sendSms($phone,$content){
        $appkey=env('SMS_APPKEY');
        $sms_data['appkey']=$appkey;
        $sms_data['mobile']=$phone;
        $sms_data['content']=$content;
        $url="http://api.jisuapi.com/sms/send";
        $curl=new Curl();
        $curl->post($url,$sms_data);
        if ($curl->error) {
            Log::debug('短信发送错误！'.'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage);
            return false;
        }
        $result=json_decode($curl->response);
        if($result['status']!=0){
            Log::debug('短信发送错误！'.'Error: ' . $result['msg']);
            return false;
        }
        return true;
    }

}