<?php
/**
 * Created by PhpStorm.
 * User: lxl
 * Date: 16-10-24
 * Time: 下午5:05
 */
namespace  App\Http\Controllers\Library;
use App\Model\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Mohuishou\Lib\Library;

class LibraryBaseController extends Controller{

    protected $_library;
    protected $_user;
    protected $_update_return=[
        "status"=>1,
        "count"=>0,
        "data"=>[],
        "msg"=>""
    ];

    public function __construct(Request $request)
    {
        parent::__construct($request);
        //todo:判断是否绑定了图书馆账号信息，如果没有绑定成功，直接返回错误
        if($this->_request->user()) {
            $this->_user = $this->_request->user();
            $id = $this->_user->userLibrary->library_id;
            $password = decrypt($this->_user->userLibrary->library_password);
            try {
                $this->_library = new Library($id, $password);
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
        }
    }

    protected function init(User $user){
        $this->_update_return=[
            "status"=>1,
            "count"=>0,
            "data"=>[],
            "msg"=>""
        ];
        $this->_user=$user;
        $id=$this->_user->userLibrary->library_id;
        $password=decrypt($this->_user->userLibrary->library_password);
        $this->_library = new Library($id, $password);

    }

    public function updateBase(User $user){
        //不通过route调用时，request对象不存在user
        if (!$this->_library){
            $this->init($user);
        }
    }
}