<?php

namespace Tests\Unit;

use Tests\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use \App\User as User;
use Illuminate\Foundation\Auth\User as Authenticatable;
use \Spatie\Permission\Traits\HasRole;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\WithoutMiddleware;


class UserTest extends TestCase
{
    use WithoutMiddleware;
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

    /** @test */
    public function test_ShowUser()
{
    $response = $this->get("/users/{$this->user->id}");
    $response->assertStatus(200);

    $response->assertJsonStructure(
            [
                    'id',
                    'name',
                    'email',
                    'email_verified_at',
                    'created_at',
                    'updated_at'
            ]
    );

    $response->assertJson(
            [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                    'email' => $this->user->email,
                    'email_verified_at' => $this->user->email_verified_at,
                    'created_at' => $this->user->created_at,
                    'updated_at' => $this->user->updated_at
            ]
    );

    }

    /** @test */
    public function test_CreateUser()
    {
    $this->newUser = factory(\App\User::class)->make();
    $response = $this->actingAs($this->user)->post('users', [
        'name' => $this->newUser->name,
        'email' => $this->newUser->email,
        'password' => $this->newUser->password,
    ]);
    $response
            ->assertStatus(201)
            ->assertExactJson([
                'created' => true,
            ]) ;

            $this->assertDatabaseHas('users', [
                'email' => $this->newUser->email,
            ]);
    }
    /** @test */
    public function test_CanNotCreateUserWithInvalidName()
    {
        $response = $this->actingAs($this->user)->post('users', [
            'name' => str_repeat('a', 51),
            'email' => $this->user->email,
            'password' => 'secret',
        ]);
        $response->assertStatus(302);
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function test_CanNotCreateUserWithInvalidEmail()
    {
        $response = $this->actingAs($this->user)->post('users', [
            'name' => $this->user->name,
            'email' => str_repeat('a', 256),
            'password' => 'secret',
        ]);
        $response->assertStatus(302);
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function test_UpdatesValidUser()
    {
        $this->newUser = factory(\App\User::class)->make();
        $attributes = factory(User::class)->raw(
            [
                'name' => $this->newUser->name,
            ]
        );
        $response = $this->put("users/{$this->user->id}", $attributes);
        $this->assertDatabaseHas('users',
            [
                'name' => $this->newUser->name
            ]
        );
        $response->assertStatus(200);
    }

    /** @test */
    public function test_CanNotUpdateInvalidUser()
    {
        $this->newUser = factory(\App\User::class)->create();
        // echo $this->user->name;
        $attributes = ['name' => 'i',];
        $response = $this->json('PATCH',"users/{$this->user->id}", []);
        $response->assertStatus(422);
    }

    /** @test */
    public function test_DeleteUser()
    {
        $response = $this->actingAs($this->user) ->delete("users/{$this->user->id}"); $response->assertStatus(200);
        $this->assertDatabaseMissing('users', ['id' => $this->user->id]);
    }

}
