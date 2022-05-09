<?php
/**
 * Created by PhpStorm.
 * User: salman
 * Date: 4/15/2020
 * Time: 7:05 PM
 */

namespace App\Http\Helpers;

use Illuminate\Support\Facades\Auth;

class UserHelper
{
    public $user;
    public function __construct(){
        $this->user = Auth::user();
    }

    public function get_user_full_name(){
        return $this->user->name;
    }
}