<?php
/**
 * Created by PhpStorm.
 * User: lxl
 * Date: 2016/12/16
 * Time: 20:34
 */

namespace App\Http\Controllers\Jwc;


use App\Http\Controllers\Controller;
use Mohuishou\Lib\ScuplusJwc;


/**
 * 用户模块基本控制器
 * Class BaseController
 * @author mohuishou <1@lailin.xyz>
 * @package App\Http\Controllers\User
 */
class BaseController extends Controller
{
    protected $_jwc_obj;

    protected $_jwc_name=null;

    public function __construct()
    {

    }

    /**
     * @author mohuishou<1@lailin.xyz>
     * @param $sid
     * @param $password
     * @return \Illuminate\Http\JsonResponse
     */
    protected function init($sid,$password){
        //初始化要操作的教务处类，默认为评教
        $this->_jwc_name || $this->_jwc_name='Evaluate';
        try {
            $this->_jwc_obj=ScuplusJwc::create($this->_jwc_name,$sid,$password);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(),20000);
        }
    }
}