<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use JWTAuth;

class TablenameControllerTest extends TestCase
{
    private static $id = null;

//    public function checkTokenFromResponse(TestResponse $response)
//    {
//        $response->assertStatus(200);
//    }

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

    public function checkTokenRequest()
    {

        $response = $this->json('POST', '/api/login', ['email' => 'trumtintac@gmail.com', 'password' => '123456']);

        self::$id = $response->original['status'];

        $this->assertFalse(!self::$id, 'Email or password incorrect');

        $user = $response->json();

        $response->assertStatus(200);

        return $user;
    }

    public function it_allow_anyone_to_see_list_all_tablename()
    {
        $response = $this->get(route('table_names'));
        $response->assertSuccessful();
    }

    public function testShow()
    {
        $response = $this->get('/api/table_names/' . self::$id);
        $response->assertStatus(401);
    }

    public function checkNullCreateOrUpdate(TestResponse $response)
    {
        if ($response->getStatusCode() == 400) {
            $this->assertNotEmpty('', 'Table_name not null');
            $response->assertStatus(400);
        }
        $response->assertStatus(200);
    }

    public function testStore()
    {

        $user = $this->checkTokenRequest();

        $token = ['HTTP_Authorization' => 'Bearer ' . $user['data']];

        $response = $this->json('POST', '/api/table_names',
            ['table_name' => "Default"], $token);

        $this->checkNullCreateOrUpdate($response);

        self::$id = $response->original['status'][true];

        $response->assertStatus(200);
    }


    public function testUpdate()
    {
        $user = $this->checkTokenRequest();

        $token = ['Authorization' => 'Bearer ' . $user['data']];

        $response = $this->json('PUT', '/api/table_names/' . self::$id,
            ['table_name' => "Request log"], $token);

        $this->checkNullCreateOrUpdate($response);

        $response->assertStatus(200);
    }

    public function testDelete()
    {
        $user = $this->checkTokenRequest();

        $token = ['Authorization' => 'Bearer ' . $user['data']];

        $response = $this->json('delete', '/api/table_names/' . self::$id, [], $token);

        if ($response->getStatusCode() == 400) {
            $response->assertStatus(400);
        } else {
            $response->assertStatus(200);
        }
    }

}
