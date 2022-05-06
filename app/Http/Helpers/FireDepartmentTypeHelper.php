<?php
/**
 * Created by PhpStorm.
 * User: KINGDOM VISION
 * Date: 19/10/2020
 * Time: 6:29 PM
 */

namespace App\Http\Helpers;


class FireDepartmentTypeHelper
{
    public static function prefix_id($id){
        return sprintf("%03s", $id);
    }
}