<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Role;
class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roleManager = Role::where('name', 'manager')->first();
        $roleAdmin = Role::where('name', 'admin')->first();
        $roleRep = Role::where('name', 'sales-rep')->first();

        // $user = new User();
        // $user->name = 'Doris Xup';
        // $user->username = 'dorisx';
        // $user->email = 'doris@gmail.com';
        // $user->password = bcrypt('rush2ghc');
        // $user->save();
        // $user->roles()->attach($roleRep);

        // $etor = new User();
        // $etor->name = 'Ernest Anyidoho';
        // $etor->username = 'etoretornam';
        // $etor->email = 'etoretornam@live.com';
        // $etor->password = bcrypt('143541');
        // $etor->save();
        // $etor->roles()->attach($roleAdmin);


        // $vit = new User();
        // $vit->name = 'Victor Anyidoho Kwasi';
        // $etor->username = 'victor';
        // $etor->email = 'victor@live.com';
        // $etor->password = bcrypt('victor');
        // $etor->save();
        // $etor->roles()->attach($roleManager);

        $da = new User();
        $da->name = 'David Ecotel';
        $da->username = 'davidecotel';
        $da->email = 'david@live.com';
        $da->password = bcrypt('davidecotel');
        $da->save();
        $da->roles()->attach($roleAdmin);
    }
}
