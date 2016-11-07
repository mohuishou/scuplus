<?php
/**
 * Created by PhpStorm.
 * User: lxl
 * Date: 16-11-7
 * Time: 下午2:04
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Scuplus extends Command{
    /**
     * 控制台命令名称
     *
     * @var string
     */
    protected $signature = 'scuplus:run';

    /**
     * 控制台命令描述
     *
     * @var string
     */
    protected $description = 'scuplus daily schedule';


    /**
     * Scuplus constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 执行控制台命令
     *
     * @return mixed
     */
    public function handle()
    {
    }
}