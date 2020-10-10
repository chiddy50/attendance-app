<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    public function employees(){
        return $this->hasMany('App\Employee', 'team_id', 'id');
    }
}
