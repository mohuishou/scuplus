<?php
/**
 * Created by PhpStorm.
 * User: lxl
 * Date: 2016/12/17
 * Time: 12:37
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    protected $dateFormat='U';

    protected $guarded = [
        'id'
    ];
}