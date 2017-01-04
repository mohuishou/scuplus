<?php
/**
 * Created by PhpStorm.
 * User: lxl
 * Date: 2016/12/18
 * Time: 21:21
 */

namespace App\Models\Jwc;


use App\Models\BaseModel;

class EvaluateCheck extends BaseModel
{
    protected $table='jwc_evaluate_check';

    public function userJwc(){
        return $this->belongsTo('App\Models\User\UserJwc','user_jwc_id');
    }
}