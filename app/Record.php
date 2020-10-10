<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    public function employee(){
        return $this->belongTo('App\Employee');
    }

    public function month(){
        return $this->belongTo('App\Month');
    }
}
