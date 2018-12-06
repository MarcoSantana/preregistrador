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
    }

    /**
     * Only registered users can see the users listing
     *
     * @return void
     */
    public function test_OnlyRegisteredUserCanListUsers()
    {
       $response = $this->get('/users');
       /* dd($response); */
       $response->assertStatus(404);
    }
    
}
