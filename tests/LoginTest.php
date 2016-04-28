<?php

use App\User;


class LoginTest extends \TestCase
{
    public function setUp()
    {
        parent::setUp();
        User::create(['name' => 'user',  'email' => 'user@example.com',  'password' => bcrypt('password')]);
    }

    public function testLoginBeforeRedirect()
    {
        $resp = $this->call('GET', '/dashboard');
        $this->assertEquals($resp->getStatusCode(), 302);
        $location = $resp->headers->get('Location');
        $this->assertEquals($location, 'http://localhost/login?before=/dashboard');
        //
    }

    public function testBeforeUrlInSession()
    {
        $this->withSession([])->call('GET', '/login?before=/dashboard');
        $this->assertSessionHas('beforeUrl');
        $this->seeInSession('beforeUrl', '/dashboard');
    }

    public function testAfterLogin()
    {
        $this->withSession([])->call('GET', '/login?before=/users/1');
        $resp = $this->call('POST', '/login', ['email' => 'user@example.com', 'password' => 'password']);
        $this->assertEquals($resp->getStatusCode(), 302);
        $location = $resp->headers->get('Location');
        $this->assertEquals($location, 'http://localhost/users/1');

    }
}
