<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Certification extends Model
{
    public function c_history()
    {
// return $this->hasMany(certificatehistory::class,'certificate_id');
return $this->hasMany('App\certificatehistory');
    }
}
