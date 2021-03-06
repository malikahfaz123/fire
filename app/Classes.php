<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    protected $table = 'classes';
    public function archived(){
        return $this->belongsTo('App\User','archived_by');
    }
}
