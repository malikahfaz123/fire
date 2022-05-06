<?php
/**
 * Created by PhpStorm.
 * User: Kingdom Vision
 * Date: 10-Jul-20
 * Time: 12:01 PM
 */

namespace App\Http\Helpers;

use Illuminate\Support\Facades\DB;

class CourseHelper
{
    public static function prefix_id($id){
        $str = "ABCEFGHIJKLMNOPQRSTUVWXYZ";
        $num = rand(0,24);
        return "C".rand(0,9).substr($str,$num,1).sprintf("%02s", $id);
    }


}