<?php

namespace Tests\Unit;

use Tests\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use \App\User as User;
use Illuminate\Foundation\Auth\User as Authenticatable;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client as Client;

class ApiLoginTest extends TestCase
{
    use RefreshDatabase;

    public function setup()
    {
        parent::setUp();
        $this->user =factory(User::class)->make(); 
        // $token = $this->user->createToken('Access Token')->accessToken;
        $this->client = new Client([
            // Base URI is used with relative requests
            'base_uri' => 'http://preregistrador.test/api/',
            'timeout'  => 2.0,
        ]);

    }

    /** @test */
    public function testUserCanSignup()
    {
        $body = [
            'name' => $this->user->name,
            'email' => $this->user->email,
            'password' => 'secret',
            'password_default' => 'secret',
        ];
        $response = $this->client->request(
            'POST',
            'auth/signup',
            [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'X-Requested-With' => 'XMLHttpRequest'
                ],
                'form_params' => [
                    'name' => $this->user->name,
                    'email' => $this->user->email,
                    'password' => 'secret',
                    'password_confirmation' => 'secret',
                ]
            ]
        );
        $code = $response->getStatusCode(); 
        $this->assertTrue($code == 201);
        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType);
        $this->assertEquals('{"message":"Usuario creado exitosamente"}', $response->getBody()->getContents());
    } 

    /** @test */
    public function testUserCanLogin()
    {
        $user =factory(User::class)->create(); 
        $response = $this->client->request(
            'POST',
            'auth/login',
            [
                'form_params' => [
                    'email' => 'prueba@prueba.com',
                    'password' => 'secret',
                ]
            ]
        );
        $body = $response->getBody()->getContents();
        $this->assertEquals($response->getStatusCode(), 200);
        $nuResponse = $this->post('auth/login', 
            [
                'form_params' => [
                    'email' => 'prueba@prueba.com',
                    'password' => 'secret'
                ]
            ]
                );
        dd($response->getBody()->getContents());
    }

}

