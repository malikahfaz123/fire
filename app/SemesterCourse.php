<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SemesterCourse extends Model
{
    public $timestamps = false;
    protected $table = 'semester_courses';

    public function course(){
        return $this->belongsTo('App\Course');
    }
}
