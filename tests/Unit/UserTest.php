<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use \App\User as User;
use Illuminate\Foundation\Auth\User as Authenticatable;
use \Spatie\Permission\Traits\HasRole;
use Spatie\Permission\Models\Role;


class UserTest extends TestCase
{
    use RefreshDatabase;
    // use DatabaseMigrations;
    // use HasRoles;
    use \Spatie\Permission\Traits\HasRoles;

    public function setUp()
    {
        // first include all the normal setUp operations
        parent::setUp();

        // now re-register all the roles and permissions
        $this->app->make(\Spatie\Permission\PermissionRegistrar::class)->registerPermissions();
        $this->user = factory(\App\User::class)->create();
    }

    public function testUserCanBeAssignedRoles()
    {
        // $user = User::first();
        // $this->assertInstanceOf('\App\User', $user);
        $this->assertInstanceOf('\App\User', $this->user);
        $role = Role::create(['name' => 'worker']);
        // $user->assignRole('worker');
        $this->user->assignRole('worker');
        // $this->assertTrue($user->hasAnyRole(Role::all()));
        $this->assertTrue($this->user->hasAnyRole(Role::all()));
    }
}
