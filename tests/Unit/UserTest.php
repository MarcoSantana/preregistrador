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
        $this->assertInstanceOf('\App\User', $this->user);
        $role = Role::create(['name' => 'worker']);
        $this->user->assignRole('worker');
        $this->assertTrue($this->user->hasAnyRole(Role::all()));
    }

    /** @test */
    public function test_ShowUsers()
    {
        $response = $this->get('/users');
        $response ->assertStatus(200) ;
        $response->assertJsonStructure(
            [
                [
                        'id',
                        'name',
                        'email',
                        'email_verified_at',
                        'created_at',
                        'updated_at'
                ]
            ]
        );
    }

}
