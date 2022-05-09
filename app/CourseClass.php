<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CourseClass extends Model
{
    protected $table = 'course_classes';

    public function firefighter(){
        return $this->belongsTo('App\Firefighter');
    }
}
