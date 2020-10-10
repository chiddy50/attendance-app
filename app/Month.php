<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Month extends Model
{
    public function records(){
        return $this->hasMany('App\Record', 'month_id', 'id');
    }
}
