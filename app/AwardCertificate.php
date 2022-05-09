<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AwardCertificate extends Model
{
    protected $table = 'awarded_certificates';

    public function certificate(){
        return $this->belongsTo('App\Certificate');
    }

    public function organization(){
        return $this->belongsTo('App\Organization');
    }
}