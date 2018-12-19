<?php

namespace Tests\Unit;

use Tests\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use \App\User as User;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ApiLoginTest extends TestCase
{
    use RefreshDatabase;

    public function setup()
    {
        parent::setUp();
        $this->user =factory(User::class)->create(); 
        // $token = $this->user->createToken('Access Token')->accessToken;

    }

    // public function testUserCanSignup()
    // {

    //     return void;
    // }

    /** @test */
   public function testUserCanSignup()
   {
       $body = [
           'name' => $this->user->name,
           'email' => $this->user->email,
           'password' => 'secret',
           'password_default' => 'secret',
       ];

       $this->json('POST','api/auth/signup', $body,
                   [
                       'Content-Type' => 'application/x-www-form-urlencoded',
                       'X-Requested-With' => 'XMLHttpRequest'
                   ])
           ->assertStatus(201)
           ->assertJsonStructure(
               [
                   'token_type',
                   'expires_in',
                   'access_token',
               ]
           );
   } 

}

