<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * 请求参数错误，http状态码422
     * @author mohuishou<1@lailin.xyz>
     * @param $msg
     * @return \Laravel\Lumen\Http\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function errorRequest($msg,$code=10000){
        Log::info($msg);
        return response()->json([
            "status"=>0,
            "msg"=>$msg,
            "code"=>$code
        ],422);
    }


    public function error($msg,$code=10000){
        Log::info($msg);
        return response()->json([
            "status"=>0,
            "msg"=>$msg,
            "code"=>$code
        ],400);
    }

    /**
     * @author mohuishou<1@lailin.xyz>
     * @param $msg
     * @param array $data
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function success($msg,$data=[]){
        $success=[
            'status'=>1,
            'code'=>200,
            'msg'=>$msg,
            'data'=>$data
        ];
        return response()->json($success,200);
    }


    /**
     * @author mohuishou<1@lailin.xyz>
     * @param $msg
     * @param int $code
     * @return array
     */
    public function errorData($msg,$code=10000){
        Log::info($msg);
        $data=[
            "status"=>0,
            "msg"=>$msg,
            "code"=>$code
        ];
        return $data;
    }

    /**
     * @author mohuishou<1@lailin.xyz>
     * @param $msg
     * @param array $data
     * @return array
     */
    public function successData($msg,$data=[]){
        $success=[
            'status'=>1,
            'code'=>200,
            'msg'=>$msg,
            'data'=>$data
        ];
        return $success;
    }

    /**
     * @author mohuishou<1@lailin.xyz>
     * @param $data
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function apiReturn($data){
        $code=$data['code'];
        if($data['status']==0){
            $code=400;
        }
        return response()->json($data,$code);
    }
}
