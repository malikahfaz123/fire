<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->id                   = 1;
        $user->role_id              = 1;
        $user->name                 = 'Richlo';
        $user->email                = 'admin@admin.com';
        $user->email_verified_at    = date('Y-m-d H:i:s');
        $user->password             = Hash::make('testing123');
        $user->created_at           = date('Y-m-d H:i:s');
        $user->updated_at           = date('Y-m-d H:i:s');
        $user->save();

        // Assign role to a user
        $role = Role::findById(1);
        $user->assignRole($role->name);
    }
}
