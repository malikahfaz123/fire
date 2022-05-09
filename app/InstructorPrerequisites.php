<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InstructorPrerequisites extends Model
{
    protected $table = 'instructor_prerequisites';

    public function course(){
        return $this->belongsTo('App\Course');
    }
}
