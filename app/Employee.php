<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Record;

class Employee extends Model
{
    //
    public function records(){
        return $this->hasMany('App\Record', 'employee_id', 'id');
    }

    public function team(){
        return $this->belongTo('App\Team');
    }
}
