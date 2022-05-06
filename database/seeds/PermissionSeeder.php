<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $modules = ['firefighters','semesters','courses','certifications','fire_departments','organizations','facilities','settings'];
        $operations = ['create','read','update','delete'];
        $count = 1;
        foreach ($modules as $module){
            foreach ($operations as $operation){
                $record = DB::table('permissions')->select(DB::raw('COUNT(id) as count'))->where('name',"{$module}.{$operation}")->limit(1)->first();
                if(!isset($record->count) || !$record->count){
                    DB::table('permissions')->insert(['id'=>"$count",'name'=>"{$module}.{$operation}",'guard_name'=>'web']);
                    $count++;
                }
            }
        }
    }
}
