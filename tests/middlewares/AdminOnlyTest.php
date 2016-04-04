<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\Middleware\AdminOnly;
use App\User;

class AdminOnlyTest extends \TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->view = app('view');
        $this->middleware = new AdminOnly($this->view);
        $this->request = new Request();
        DB::transaction(function ()
        {
            $user = User::create(['name'=>'test_user_1', 'email' => 'user1@example.com', 'password' => '']);
            $user->is_admin = true;
            $user->save();
            User::create(['name'=>'test_user_2', 'email' => 'user2@example.com', 'password' => '']);
        });
    }

    /**
     */
    public function testIsAdmin()
    {
        $this->be(User::find(1));
        $result = $this->middleware->handle($this->request, function($request){ return 'OK';});
        $this->assertEquals($result, 'OK');
    }

    /**
     */
    public function testNotAdmin()
    {
        $this->be(User::find(2));
        $result = $this->middleware->handle($this->request, function($request){});
        $this->assertEquals($result->getStatusCode(), 401);
    }
}
