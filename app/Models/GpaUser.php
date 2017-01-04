<?php
/**
 * Created by PhpStorm.
 * User: lxl
 * Date: 2016/12/17
 * Time: 12:59
 */

namespace App\Models;



class GpaUser extends BaseModel
{
    protected $table="gpa_user";

    protected $fillable = ['student_id', 'password'];



}