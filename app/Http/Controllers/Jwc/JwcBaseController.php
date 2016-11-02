<?php
/**
 * Created by mohuishou<1@lailin.xyz>.
 * User: mohuishou<1@lailin.xyz>
 * Date: 2016/8/18 0018
 * Time: 15:26
 */
namespace App\Http\Controllers\Jwc;
use App\Http\Controllers\Controller;
use App\Model\User;
use Illuminate\Http\Request;
use Mohuishou\Lib\ScuplusJwc;

class JwcBaseController extends Controller{

    /**
     * @var string
     */
    protected $_jwc_obj;

    /**
     * @var null|string
     */
    protected $_jwc_name=null;

    protected $_user;

    protected $_update_return=[
        "status"=>1,
        "count"=>0,
        "data"=>[],
        "msg"=>""
    ];

    /**
     * JwcBaseController constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
        if(!empty($this->_request->user())){
            $this->init($this->_request->user());
        }
    }

    /**
     * 初始化
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function init(User $user){

        $this->_update_return=[
            "status"=>1,
            "count"=>0,
            "data"=>[],
            "msg"=>""
        ];

        $jwc=$user->userJwc;
        if(!isset($jwc->verify)||$jwc->verify!=1){
            return $this->error("教务处未绑定，或账号密码错误！",24011);
        }
        $id=$user->userJwc->jwcid;
        $password=decrypt($user->userJwc->jwc_password);
        $this->_user=$user;
        //初始化要操作的教务处类，默认为评教
        $this->_jwc_name || $this->_jwc_name='Evaluate';
        try {
            $this->_jwc_obj=ScuplusJwc::create($this->_jwc_name,$id,$password);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(),20000);
        }

    }

    /**
     * 更新方法
     * @param User $user
     * @return mixed
     */
    public function updateBase(User $user){
        //不通过route调用时，request对象不存在user
        if (!$this->_jwc_obj){
            $this->init($user);
        }
    }



    /**
     * 更改教务处绑定状态
     * @param int $status
     * @param null $user_jwc
     * @return bool
     */
    protected function verify($status=0,$user_jwc=null){
        $user_jwc || $user_jwc=$this->_user->userJwc;
        $user_jwc->verify=$status;
        if($user_jwc->save()){
            return true;
        }
        return false;
    }
}