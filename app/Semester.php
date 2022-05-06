<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    public function courses(){
        return $this->hasMany('App\SemesterCourse');
    }

    public function added_by(){
        return $this->belongsTo('App\User','created_by');
    }

    public function archived(){
        return $this->belongsTo('App\User','archived_by');
    }
}
