<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FireDepartment extends Model
{
    public function archived(){
        return $this->belongsTo('App\User','archived_by');
    }
}
