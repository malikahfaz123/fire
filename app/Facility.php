<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    public function archived(){
        return $this->belongsTo('App\User','archived_by');
    }
}
