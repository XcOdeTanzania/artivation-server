<?php

use App\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = new Role();
        $role->name = 'Administrator';
        $role->save();

        $role = new Role();
        $role->name = 'Artist';
        $role->save();

        $role = new Role();
        $role->name = 'User';
        $role->save();
    }
}
