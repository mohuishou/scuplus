<?php
/**
 * Created by PhpStorm.
 * User: lxl
 * Date: 16-10-24
 * Time: 下午3:32
 */

namespace App\Model;
class LibraryHistory extends BaseModel{
    protected $table = 'library_history';

    protected $guarded = [
        'id'
    ];


    public function user(){
        return $this->belongsTo('App\Model\User', 'uid');
    }
}