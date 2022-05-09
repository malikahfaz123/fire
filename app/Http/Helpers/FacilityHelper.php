<?php
/**
 * Created by PhpStorm.
 * User: KINGDOM VISION
 * Date: 15/07/2020
 * Time: 12:53 PM
 */

namespace App\Http\Helpers;
use App\FacilityType;
use App\ForeignRelations;


class FacilityHelper
{
    public static function prefix_id($id){
        return 'F'.sprintf("%05s", $id);
    }

    public static function get_type($facility_id){
        $types = ForeignRelations::select('value')->where('foreign_id',$facility_id)->where('module','facilities')->where('name','facility_type')->get();
        foreach ($types as $type){
            $facility = FacilityType::select('description')->where('id',$type->value)->limit(1)->first();
            $arr[$type->value] = $facility->description;
        }
        return $arr;
    }
}