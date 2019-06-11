<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserControllerTest extends TestCase
{
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

    public function checkEmail()
    {
        $response = $this->json('POST', '/api/login', ['email' => '']);

        $response->assertStatus(400);
    }

    public function checkPassword()
    {
        $response = $this->json('POST', '/api/login', ['password' => '']);

        $response->assertStatus(400);
    }

    public function checkRegister()
    {
        $response = $this->json('POST', '/api/login', ['email' => '','password' => '']);

        $response->assertStatus(400);
    }

    public function testRegister()
    {
        $this->checkEmail();
        $this->checkPassword();
        $this->checkRegister();


    }
}
