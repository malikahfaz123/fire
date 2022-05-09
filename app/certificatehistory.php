<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class certificatehistory extends Model
{
    protected $table = 'certificatehistories';


    public function certificate()
    {
        // return $this->belongsTo(Certificate::class,'certificate_id','id');
        return $this->belongsTo('App\Certification');
    }

    public function firefighter()
    {
        // return $this->belongsTo(Firefighter::class,'firefighter_id','id');
        return $this->belongsTo('App\Firefighter');
    }
}
