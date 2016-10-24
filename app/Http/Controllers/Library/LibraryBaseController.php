<?php
/**
 * Created by PhpStorm.
 * User: lxl
 * Date: 16-10-24
 * Time: 下午5:05
 */
namespace  App\Http\Controllers\Library;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Mohuishou\Lib\Library;

class LibraryBaseController extends Controller{

    protected $_library;
    protected $_user;

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->_user=$this->_request->user();
        $id=$this->_user->userLibrary->library_id;
        $password=decrypt($this->_user->userLibrary->library_password);
        try {
            $this->_library = new Library($id, $password);
        }catch (\Exception $e){
            return $this->error($e->getMessage());
        }
    }
}