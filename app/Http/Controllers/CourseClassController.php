<?php

namespace App\Http\Controllers;

use App\CourseClass;
use App\History;

class CourseClassController extends Controller
{
    public function history($course_id,$firefighter_id){
        $course_classes = CourseClass::where('course_id',$course_id)->where('firefighter_id',$firefighter_id)->get();
        $course_class_ids = [];
        foreach ($course_classes as $course_class){
            $course_class_ids[] = $course_class->id;
        }
        $histories = History::whereIn('foreign_id',$course_class_ids)->where('module','course_classes')->get();
        if($histories && $histories->count()){
            foreach ($histories as $key=>$history){
                $temp = CourseClass::select('class_id')->where('id',$history->foreign_id)->limit(1)->first();
                $histories[$key]->class_id = $temp->class_id;
            }
            return view('firefighter.update-history')->with('histories',$histories);
        }
    }
}
