<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use App\User;


class AuthenticateControllerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        DB::transaction(function ()
        {
            User::create(['name'=>'test_user_1', 'email' => 'user1@example.com', 'password' => bcrypt('password1')]);
            User::create(['name'=>'test_user_2', 'email' => 'user2@example.com', 'password' => bcrypt('password2')]);
        });

    }

    /**
     * @return void
     */
    public function testOk()
    {
        $response = $this->call('POST', '/api/authenticate', [
            'email' => 'user1@example.com',
            'password' => 'password1',
        ]);
        $ctype = $response->headers->get('content_type');
        $this->assertEquals($ctype, 'application/json');
        $respJson = json_decode($response->getContent());
        $this->assertObjectHasAttribute('token', $respJson);
    }

    /**
     * @return void
     */
    public function testNg()
    {
        $patterns = [
            ['email' => 'user1@example.com', 'password' => 'password'],
            ['email' => 'user3@example.com', 'password' => 'password2'],
        ];
        $testFunction = function ($credentials)
        {
            $response = $this->call('POST', '/api/authenticate', $credentials);
            $this->assertEquals($response->getStatusCode(), 401);
            $ctype = $response->headers->get('content_type');
            $this->assertEquals($ctype, 'application/json');
            $respJson = json_decode($response->getContent());
            $this->assertObjectHasAttribute('error', $respJson);
            $this->assertEquals($respJson->error, 'invalid_credentials');
        };
        array_map($testFunction, $patterns);
    }

}
