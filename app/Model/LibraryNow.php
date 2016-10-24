<?php
/**
 * Created by PhpStorm.
 * User: lxl
 * Date: 16-10-24
 * Time: 下午3:34
 */

namespace App\Model;


class LibraryNow extends BaseModel
{
    protected $table = 'library_now';

    protected $guarded = [
        'id'
    ];


    public function user(){
        return $this->belongsTo('App\Model\User', 'uid');
    }
}