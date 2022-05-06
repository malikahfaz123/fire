<?php
/**
 * Created by PhpStorm.
 * User: KINGDOM VISION
 * Date: 14/07/2020
 * Time: 12:12 PM
 */

namespace App\Http\Helpers;


class OrganizationHelper
{
    public static function prefix_id($id){
        return 'E'.sprintf("%05s", $id);
    }
}