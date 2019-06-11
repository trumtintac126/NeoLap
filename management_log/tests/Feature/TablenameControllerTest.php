<?php

namespace Tests\Feature;
use Tests\TestCase;


class TablenameControllerTest extends TestCase
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

    public function testShowSuccess()
    {
        $response = $this->json('Get', '/api/table_names', [], self::$token);
        $response->assertStatus(200);
    }

    public function testShowFailToken()
    {
        $response = $this->json('Get', '/api/table_names', [], ['Authorization' => 'Bearer ' . '']);
        $response->assertStatus(401);
    }

    public function testStoreFailToken()
    {
        $response = $this->json('POST', '/api/table_names',
            ['table_name' => "Default"], ['Authorization' => 'Bearer ' . '']);

        $response->assertStatus(401);
    }

    public function testStoreFail()
    {
        $response = $this->json('POST', '/api/table_names',
            ['table_name' => ""], self::$token);

        $response->assertStatus(400);
    }

    public function testStoreSuccess()
    {
        $response = $this->json('POST', '/api/table_names',
            ['table_name' => "Default"], self::$token);

        $response->assertStatus(200);
    }

    public function testUpdateFailToken()
    {
        $response = $this->json('PUT', '/api/table_names/13',
            ['table_name' => "Default"], ['Authorization' => 'Bearer ' . '']);
        $response->assertStatus(401);
    }

    public function testUpdateFailId()
    {
        $response = $this->json('PUT', '/api/table_names/1',
            ['table_name' => "Request"], self::$token);

        $response->assertStatus(400);
    }

    public function testUpdateSuccess()
    {
        $response = $this->json('PUT', '/api/table_names/13',
            ['table_name' => "Request log"], self::$token);

        $response->assertStatus(200);
    }

//    public function testDelete()
//    {
//        $user = $this->checkTokenRequest();
//
//        $token = ['Authorization' => 'Bearer ' . $user['data']];
//
//        $response = $this->json('delete', '/api/table_names/' . self::$id, [], $token);
//
//        if ($response->getStatusCode() == 400) {
//            $response->assertStatus(400);
//        } else {
//            $response->assertStatus(200);
//        }
//    }

}
