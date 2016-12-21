<?php
/**
 * Created by PhpStorm.
 * User: lxl
 * Date: 2016/12/17
 * Time: 22:33
 */

namespace App\Http\Controllers\Jwc;


use App\Http\Controllers\User\UserJwcController;
use App\Jobs\Jwc\EvaluateUpdateJob;
use App\Models\Jwc\EvaluateCheck;
use App\Models\Jwc\EvaluateUpdate;
use App\Models\User\UserJwc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EvaluateController extends BaseController
{
    protected $_jwc_name='Evaluate';

    public function index(Request $request,UserJwcController $userJwcController){
        //字段验证，学号，密码
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

        //验证用户
        $res=$userJwcController->show($sid,$password,1);

        //验证失败，返回错误
        if(!$res['status']){
            return $this->apiReturn($res);
        }

        //抓取评教信息
        $data=$this->evaluateData($sid,$password,$res['data']['user_jwc_id']);
        $data['token']=$res['data']['token'];
        return $this->success('获取成功',$data);
    }

    /**
     * 添加到更新队列
     * @author mohuishou<1@lailin.xyz>
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addJob(Request $request){
        $this->validate($request,[
            'id'=>'required',
            'comment'=>'required',
            'star'=>'required'
        ],[
            'id.required'=>'id必须！',
            'comment.required'=>'评论必须！',
            'star.required'=>'评分必须！'
        ]);

        //保存到数据库
        $eva_model=EvaluateUpdate::find($request->input('id'));
        $eva_model->comment=$request->input('comment');
        $eva_model->star=$request->input('star');
        $eva_model->save();

        $sid=$eva_model->userJwc->student_id;
        $password=decrypt($eva_model->userJwc->password);


        //添加到队列
        $this->dispatch((new EvaluateUpdateJob($sid,$password,$request->input('id')))->onQueue("evaluate"));

        return $this->success('成功添加到后台队列！');

    }

    /**
     * @author mohuishou<1@lailin.xyz>
     * @param $student_id
     * @param $password
     */
    public function evaluateData($student_id,$password,$user_jwc_id=0){
        $this->init($student_id,$password);
        try {
            $data=$this->_jwc_obj->index();
        } catch (\Exception $e) {
            return $this->error($e->getMessage(),20000);
        }

        $num=[
            'evaluated'=>0,
            'not_evaluate'=>0
        ];
//        print_r($data);
        foreach ($data['info'] as $v){
            $tmp=$v;
            $tmp['verify_name']=$data['verify']['name'];
            $tmp['verify_value']=$data['verify']['value'];
            $eva_model=EvaluateUpdate::firstOrCreate([
                'user_jwc_id'=>$user_jwc_id,
                'jwc_teacher_id'=>$tmp['jwc_teacher_id']
            ]);
            $eva_model->fill($tmp);
            $eva_model->save();
            if($v['status']==1){
                $num['evaluated']++;
                continue;
            }
            $num['not_evaluate']++;
        }
        $eva_data=EvaluateUpdate::where('user_jwc_id',$user_jwc_id)->where('status','0')->get();
        $return_data['num']=$num;
        $return_data['evaluate_data']=$eva_data;
        return $return_data;
    }

    /**
     * @author mohuishou<1@lailin.xyz>
     * @param $student_id
     * @param $password
     * @param $id
     * @return array
     */
    public function postEvaluate($student_id,$password,$id){
        $this->init($student_id,$password);
        $eva_model=EvaluateUpdate::find($id);
        try{
            $res=$this->_jwc_obj->evaluate($eva_model->toArray());
        }catch (\Exception $e){
            Log::info($e->getMessage());
            return $this->errorData($e->getMessage(),$e->getCode());
        }
        if($res['status']){
            $eva_model->status=1;
            $eva_model->save();
            return $this->successData($res['message']);
        }

        return $this->errorData($res['message']);
    }

    //对评教失败的用户重新评教
    public function reEvaluate(){
        $users=UserJwc::all();
        foreach ($users as $user){
            if(!isset($user->userCheck->check)||$user->userCheck->check!=1){
                $password=decrypt($user->password);
                $sid=$user->student_id;

                $this->evaluateData($sid,$password,$user->id);

                $eva_models=$user->evaluateUpdate()->where('status',0)->get();
                if(!isset($eva_models[0])){
                    $eva_check_model=EvaluateCheck::firstOrCreate(['user_jwc_id'=>$user->id]);
                    $eva_check_model->check=1;
                    $eva_check_model->save();
                }
                foreach ($eva_models as $eva_model){
                    if($eva_model->star&&$eva_model->comment){
                        $this->dispatch((new EvaluateUpdateJob($sid,$password,$eva_model->id))->onQueue("evaluate"));
                    }
                }
            }
        }
    }

}