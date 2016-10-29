<?php
/**
 * Created by PhpStorm.
 * User: lxl
 * Date: 16-10-29
 * Time: 下午1:45
 */

namespace App\Model;


class Message extends BaseModel
{
    protected $table='message';
    protected $guarded = [
        'id'
    ];

}