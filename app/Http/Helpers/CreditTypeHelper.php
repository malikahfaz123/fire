<?php
/**
 * Created by PhpStorm.
 * User: KINGDOM VISION
 * Date: 14/07/2020
 * Time: 3:25 PM
 */

namespace App\Http\Helpers;


class CreditTypeHelper
{
    public static function prefix_id($id){
        return sprintf("%03s", $id);
    }
}