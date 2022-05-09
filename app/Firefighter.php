<?php

namespace App;

// use Illuminate\Database\Eloquent\Model;
use App\Notifications\FirefighterResetPasswordNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Firefighter extends Authenticatable
{
    use Notifiable;

    protected $guard = "firefighters";

    public function added_by(){
        return $this->belongsTo('App\User','created_by');
    }

    public function archived(){
        return $this->belongsTo('App\User','archived_by');
    }

    public function getData()
    {
        return $this;
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new FirefighterResetPasswordNotification($token));
    }

    public function f_history()
    {
    return $this->hasMany(certificatehistory::class,'firefighter_id');
    }
}
