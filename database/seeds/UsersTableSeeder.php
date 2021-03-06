<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        factory(App\User::class, 15)->create()->each(function ($u)
        {
          $u->assignRole('worker');
        });

        factory(App\User::class, 3)->create()->each(function ($u)
        {
          $u->assignRole('supervisor');
        });

        factory(App\User::class, 1)->create()->each(function ($u)
        {
          $u->assignRole('super-admin');
        });

    }
}
