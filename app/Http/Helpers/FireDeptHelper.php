<?php
/**
 * Created by PhpStorm.
 * User: KINGDOM VISION
 * Date: 19/10/2020
 * Time: 12:04 PM
 */

namespace App\Http\Helpers;


class FireDeptHelper
{

    public static function prefix_id($id){
        return 'F'.sprintf("%04s", $id);
    }
}