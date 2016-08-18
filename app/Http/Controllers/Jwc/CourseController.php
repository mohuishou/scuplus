<?php
/**
 * Created by mohuishou<1@lailin.xyz>.
 * User: mohuishou<1@lailin.xyz>
 * Date: 2016/8/18 0018
 * Time: 15:24
 */
namespace App\Http\Controllers\Jwc;
class CourseController extends JwcBaseControoler{
    protected $_jwc_name='Course';

    public function index(){
        return $this->_jwc_obj->index();
    }

}