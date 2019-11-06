<?php

use App\Role;
use App\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->username = 'Admin';
        $user->password =  bcrypt('admin');
        $user->photo_url = 'http://www.artivation.co.tz/backend/api/piece/image/male.png';
        $user->email = 'admin@artivation.co.tz';
        $user->role_id = Role::where('name','Administrator')->first()->id;
        $user->save();
    }
}
