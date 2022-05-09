<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'id' => 1,
            'name' => 'admin',
            'guard_name' => 'web',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        // Assign multiple permissions to a role
        $modules = ['firefighters','semesters','courses','certifications','fire_departments','organizations','facilities','settings'];
        $operations = ['create','read','update','delete'];
        $role = Role::find(1);
        foreach ($modules as $module){
            foreach ($operations as $operation){
                $role->givePermissionTo("{$module}.{$operation}");
            }
        }
    }
}
