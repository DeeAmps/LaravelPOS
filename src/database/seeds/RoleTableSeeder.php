<?php

use Illuminate\Database\Seeder;
use App\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $owner = new Role();
        $owner->name         = 'manager';
        $owner->description  = 'User is the owner of the shop'; // optional
        $owner->save();

        $admin = new Role();
        $admin->name         = 'admin';
        $admin->description  = 'User is allowed to manage and edit other Products'; // optional
        $admin->save();

        $rep = new Role();
        $rep->name         = 'sales-rep';
        $rep->description  = 'Sales representative person'; // optional
        $rep->save();

    }
}
