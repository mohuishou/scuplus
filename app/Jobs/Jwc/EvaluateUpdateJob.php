<?php
/**
 * Created by PhpStorm.
 * User: lxl
 * Date: 2016/12/18
 * Time: 23:51
 */

namespace App\Jobs\Jwc;


use App\Http\Controllers\Jwc\EvaluateController;
use App\Jobs\Job;

class EvaluateUpdateJob extends Job
{
    protected $_sid;
    protected $_password;
    protected $_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($sid,$password,$id)
    {
        $this->_sid=$sid;
        $this->_password=$password;
        $this->_id=$id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(EvaluateController $evaluateController)
    {
        $re=$evaluateController->postEvaluate($this->_sid,$this->_password,$this->_id);
//        if (!isset($re['status'])||$re['status']!=1) {
//            $this->release(10);
//        }
    }
}