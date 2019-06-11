<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserControllerTest extends TestCase
{
    private static $id = null;

    private static $token = [
        'Authorization' => 'Bearer ' .
            'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC91c2VycyIsImlhdCI6MTU2MDIzN' .
            'jQ3NCwiZXhwIjoxNTYwODQxMjc0LCJuYmYiOjE1NjAyMzY0NzQsImp0aSI6IlVTZnoyalhLM3gxdUE4YUsiLCJzdWIiOjM2LCJwcnYiOiIyM2JkNWM4O' .
            'TQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.Mwwn9tAZ-yhbAc2IcPQ6AaglNEkv9pn8QAGJFeSIeYQ'
    ];

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function checkNullCreate()
    {
        $response = $this->json('POST', '/api/users', ['email' => '', 'password' => '', 'first_name' => '', 'last_name' => '']);

        $response->assertStatus(400);
    }

    public function checkRegister()
    {
        $response = $this->json('POST', '/api/users',
            ['email' => 'nguyenmanhtri@gmail.com', 'password' => '123456',
                'first_name' => 'trum', 'last_name' => 'tintac']);

        $response->assertStatus(400);
    }

    public function checktRegisterSuccess()
    {
        $user = ['email' => 'nguyenmanhtri12@gmail.com',
            'password' => '123456',
            'first_name' => 'trum',
            'last_name' => 'tintac'];

        $response = $this->json('POST', '/api/users', $user);

        self::$id = $response->original['status'];

        if (self::$id == true) {

            $response->assertStatus(200);

            $this->assertDatabaseHas('customer_users', $user);
        }
    }

    public function checkNullToken(TestResponse $response)
    {
        $response->assertStatus(401);
    }

    public function checkIdWithToken()
    {
        $response = $this->json('PUT', '/api/users/35', [], self::$token);
        $response->assertStatus(400);
    }

    public function updateSucess()
    {
        $user_update = [
            'first_name' => 'nguyen',
            'last_name' => 'manh'
        ];

        $response = $this->json('PUT', '/api/users/36', $user_update, self::$token);
        $response->assertStatus(200);
        $this->assertDatabaseHas('customer_users', ['id' => 36, 'first_name' => 'nguyen', 'last_name' => 'manh']);

    }

    public function testLoginSuccess()
    {
        $user_login = [
            'email' => 'nguyenmanhtri12@gmail.com',
            'password' => '123456'
        ];

        $response = $this->json('post', '/api/login', $user_login);
        $response->assertStatus(200);
        $this->assertTrue(Auth::check());

    }

    public function testLoginFail()
    {
        $user_login = [
            'email' => 'nguyenmanhtri123456@gmail.com',
            'password' => '123456'
        ];

        $response = $this->json('post', '/api/login', $user_login);
        $response->assertStatus(400);
    }

    public function testLogout()
    {
        $response = $this->json('POST', '/api/logout', [], self::$token);
        $response->assertStatus(200);
    }

    public function testLogoutFail()
    {
        $response = $this->json('POST', '/api/logout', [], ['Authorization' => 'Bearer ' . '']);
        $response->assertStatus(401);
    }

    public function testDeleteFailToken()
    {
        $response = $this->json('DELETE', '/api/users/36', [], ['Authorization' => 'Bearer ' . '']);
        $response->assertStatus(401);
    }

    public function testDeleteFailId()
    {
        $response = $this->json('DELETE', '/api/users/35', [], self::$token);
        $response->assertStatus(400);
    }

    public function testDeleteFailExitTableName()
    {
        $response = $this->json('DELETE', '/api/users/36', [], self::$token);
        $response->assertStatus(400);
    }

//    public function testDeleteSuccess()
//    {
//        $response = $this->json('DELETE', '/api/users/36', [], self::$token);
//        $response->assertStatus(200);
//    }

    public function testRegister()
    {
        $this->checkNullCreate();
        $this->checkRegister();
        $this->checktRegisterSuccess();
    }

    public function testUpdate()
    {
        $this->checkIdWithToken();
        $this->updateSucess();
    }

}
