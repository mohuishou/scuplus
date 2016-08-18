<?php
/**
 * Created by mohuishou<1@lailin.xyz>.
 * User: mohuishou<1@lailin.xyz>
 * Date: 2016/8/18 0018
 * Time: 15:26
 */
namespace App\Http\Controllers\Jwc;
use App\Http\Controllers\Controller;
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

    public function __construct(Request $request)
    {
        parent::__construct($request);

        $sid=$this->_request->user()->sid;
        $spassword=decrypt($this->_request->user()->spassword);

        //初始化要操作的教务处类，默认为评教
        $this->_jwc_name || $this->_jwc_name='Evaluate';
        $this->_jwc_obj=ScuplusJwc::create($this->_jwc_name,$sid,$spassword);
    }
}