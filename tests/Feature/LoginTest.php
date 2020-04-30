<?php

namespace Techneved\Admin\Login\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Techneved\Admin\Login\Tests\Traits\Custom ;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginTest extends TestCase
{
    use Custom, RefreshDatabase;
    
    protected $admin;
    
    public function setUp():void
    {
        parent::setUp();
        
        $this->admin = $this->createAdmin();
        $this->actingAs($this->admin, 'admin-logins');
    }
    /**
     * Check required fields validations of admin login
     *
     * @return void
     * @test
     */
    public function check_required_fields_validations()
    {
        $credentails = [
            'admin_id' => '',
            'password' => '',
        ];

        $response = $this->postJson(route('admin.login'), $credentails);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertExactJsonStructure([
            'admin_id',
            'password',
         ], $response, 'errors');
    }

    /**
     * Check valid credentials of admin login
     *
     * @return void
     * @test
     */
    public function check_valid_credentials()
    {

        $credentails = [
           'admin_id' => $this->admin->admin_id,
            'password' => 'password',
        ];

        $response = $this->postJson(route('admin.login'), $credentails);
        $response->assertStatus(Response::HTTP_OK);
        $this->assertExactJsonStructure([
           'access_token' ,
           'token_type' ,
           'expires_in',
           'admin'
       ], $response, 'data');
    }

    /**
    * Check invalid credentials of admin login
    *
    * @return void
    * @test
    */
    public function check_invalid_credentials()
    {
        $credentails = [
            'admin_id' => 1234567899,
            'password' => 'asfdasd',
        ];

        $response = $this->postJson(route('admin.login'), $credentails);
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $this->assertExactJsonStructure([
            'error'
        ], $response, 'errors');
    }

    /**
    * Check logout of admin login
    *
    * @return void
    * @test
    */
    public function check_logout_credentials()
    {
        $this->assertTrue(auth('admin-logins')->check());
        $token = JWTAuth::fromUser($this->admin);
        $response_second = $this->withHeaders([
            'Accept'        => 'application/json',
            'Content-Type'  => 'application/json',
            'Authorization' => 'Bearer '.$token
        ])
        ->postJson(route('admin.logout'));
        
        $response_second->assertStatus(Response::HTTP_OK);
        $this->assertFalse(auth('admin-logins')->check());
    }

    /**
    * Check unactive status with valid credentials of admin login
    *
    * @return void
    * @test
    */
    public function check_unactive_status_with_valid_credentials()
    {

        $admin = $this->createAdmin(['status' => 0]);
        $credentails = [
            'admin_id' => $admin->admin_id,
            'password' => 'password',
        ];
        $response = $this->postJson(route('admin.login'), $credentails);
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $this->assertExactJsonStructure([
            'error'
        ], $response, 'errors');
    }

    
}

