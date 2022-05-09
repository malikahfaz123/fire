<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    public function getUpdatedAtColumn() {
        return null;
    }

    public function user(){
        return $this->belongsTo('App\User');
    }
}
