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

    public function __construct(Request $request)
    {
        //todo：判断教务处是否绑定成功
        parent::__construct($request);
        if(!empty($this->_request->user())){
            $sid=$this->_request->user()->sid;
            $spassword=decrypt($this->_request->user()->spassword);
            $this->_user=$this->_request->user();
            //初始化要操作的教务处类，默认为评教
            $this->_jwc_name || $this->_jwc_name='Evaluate';
            $this->_jwc_obj=ScuplusJwc::create($this->_jwc_name,$sid,$spassword);
        }
    }

    protected function init(User $user){
        $this->_update_return=[
            "status"=>1,
            "count"=>0,
            "data"=>[],
            "msg"=>""
        ];
        $sid=$user->sid;
        $spassword=decrypt($user->spassword);
        $this->_user=$user;
        //初始化要操作的教务处类，默认为评教
        $this->_jwc_name || $this->_jwc_name='Evaluate';
        $this->_jwc_obj=ScuplusJwc::create($this->_jwc_name,$sid,$spassword);
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
}