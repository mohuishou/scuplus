<?php
/**
 * Created by PhpStorm.
 * User: lxl
 * Date: 16-11-2
 * Time: 上午10:48
 */

namespace App\Http\Controllers\User;


use App\Http\Controllers\Controller;
use App\Jobs\Jwc\JwcJob;
use App\Jobs\Library\LibraryJob;
use App\Model\UserJwc;
use App\Model\UserLibrary;
use Mohuishou\Lib\Library;
use Mohuishou\Lib\ScuplusJwc;

/**
 * 用户绑定类
 *
 * Class UserBind
 * @package App\Http\Controllers\User
 */
class UserBindController extends Controller
{
    /**
     * 教务处绑定
     * @return \Laravel\Lumen\Http\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function jwc(){
        $this->validate($this->_request, [
            'sid' => 'required|min:10',
            'spassword' => 'required|min:6|max:20',
        ]);

        $sid=$this->_request->input('sid');
        $spassword=$this->_request->input('spassword');
        try{
            $user_info=ScuplusJwc::create('Student',$sid,$spassword)->index();
        }catch (\Exception $e){
            return $this->error($e->getMessage(),"2".$e->getCode());
        }


        if($user_info){
            $user_jwc_model=UserJwc::firstOrCreate(["uid"=>$this->_request->user()->id]);
            $user_jwc_model->jwc_id=$sid;
            $user_jwc_model->jwc_password=$sid;
            $user_jwc_model->verify=1;
            if($user_jwc_model->save()){
                //添加到后台更新队列当中，更新当前用户有关教务处的所有信息
                $jwc_job=(new JwcJob($this->_request->user()))->onQueue("jwc");
                $this->dispatch($jwc_job);
                return $this->success('教务处绑定成功！，用户信息更新成功！');
            }
        }
        return $this->error('教务处绑定失败！数据库错误');
    }

    /**
     * 图书馆绑定
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function library(){
        $this->validate($this->_request, [
            'library_id' => 'required|min:10',
            'library_password' => 'required|min:6|max:20',
        ]);
        $library_id=$this->_request->input("library_id");
        $library_password=$this->_request->input("library_password");
        try{
            $library=new Library($library_id,$library_password);
            $library->loanNow();
        }catch (\Exception $e){
            return $this->error($e->getMessage());
        }

        $user_library_model=UserLibrary::firstOrCreate(['uid'=>$this->_request->user()->id]);
        $user_library_model->verify=1;
        $user_library_model->library_id=$library_id;
        $user_library_model->library_password=encrypt($library_password);

        if($user_library_model->save()){
            //添加到后台更新队列当中，更新当前用户有关图书馆的所有信息
            $library_job=(new LibraryJob($this->_request->user()))->onQueue("library");
            $this->dispatch($library_job);
            return $this->success("图书馆账号绑定成功！");
        }
        return $this->error("图书馆账号绑定失败！");
    }

    public function weChat(){

    }

    public function qq(){

    }

    public function weibo(){

    }
}