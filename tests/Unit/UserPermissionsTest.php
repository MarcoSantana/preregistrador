<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use \App\User as User;
use Illuminate\Foundation\Auth\User as Authenticatable;
use \Spatie\Permission\Traits\HasRole;
use Spatie\Permission\Models\Role;

class UserPermissionsTest extends TestCase
{
    use RefreshDatabase;
    use \Spatie\Permission\Traits\HasRoles;

    public function setUp()
    {
        // first include all the normal setUp operations
        parent::setUp();
        $this->app->make(\Spatie\Permission\PermissionRegistrar::class)->registerPermissions();
        $this->user = factory(\App\User::class)->create();
        //TODO Create a user of each kind. Factory??
    }

    /**
     * Only registered users can see the users listing
     *
     * @return void
     */
    public function test_OnlyRegisteredUserCanListUsers()
    {
        return true;
        $response = $this->get('api/users');
        $response->assertStatus(302);
        $response = $this->actingAs($this->user)->get('api/users');
        /* $response->assertStatus(200); */
        /* $this->assertAuthenticatedAs($this->user); */
        $response
            /* ->assertStatus(200) */
            ->assertJsonStructure(
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
            )
            ;
    }


    /**
     * Only registered users can see the users listing
     *
     * @return void
     */
    public function test_RegisteredUserCanListUsers()
    {
        $response = $this->actingAs($this->user)->get('api/users');
        $response
            // ->assertStatus(200)
            ->assertJsonStructure(
                [
                    'id',
                    'name',
                    'email',
                    'email_verified_at',
                    'created_at',
                    'updated_at'
                ]
            );
    }


    /**
     * A valid user can be logged in.
     *
     * @return void
     */
    public function testLoginAValidUser()
    {
        $user = factory(User::class)->create();
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'secret'
        ]);
        $response->assertStatus(302);
        $this->assertAuthenticatedAs($user);
    }

    /**
     * An invalid user cannot be logged in.
     *
     * @return void
     */
    public function testDoesNotLoginAnInvalidUser()
    {
        $user = factory(User::class)->create();
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'invalid'
        ]);
        $response->assertSessionHasErrors();
        $this->assertGuest();
    }
}
