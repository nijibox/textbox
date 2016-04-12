<?php

use Symfony\Component\Console\Tester\CommandTester;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Console\Commands\AdminSet;


class AdminSetTest extends \TestCase
{
    protected function setTestData()
    {
        DB::transaction(function ()
        {
            User::create(['name'=>'test_user_1', 'email' => 'user1@example.com', 'password' => '']);
        });

    }

    public function setUp() {
        // $this->store = m::mock('App\Services\Store');
        $this->application = $this->createApplication();
        $this->command = new AdminSet();
        $this->command->setLaravel($this->application->make('Illuminate\Contracts\Foundation\Application'));
        $this->command->setApplication($this->application->make('Symfony\Component\Console\Application'));
        parent::setUp();
        $this->setTestData();
    }

    /**
     * 存在しないメールアドレすを指定した場合は、エラーメッセージが出ること
     *
     * @return void
     */
    public function testEmailIsNotExists()
    {
        $tester = new CommandTester($this->command);
        $tester->execute([
            'email' => 'not_exists@example.com',
        ]);
        // 引数に'not_found@example.com'を指定してコマンド実行
        $user = User::find(1)->first();
        $this->assertEquals($user->is_admin, '0');
    }

    /**
     * 存在するメールアドレスが指定された場合は、管理者権限が付与されていること
     *
     * @return void
     */
    public function testEmailIsExists()
    {
        $user = User::find(1)->first();
        $this->assertEquals($user->is_admin, '0');
        $tester = new CommandTester($this->command);
        $tester->execute([
            'email' => 'user1@example.com',
        ]);
        $user = User::find(1)->first();
        $this->assertEquals($user->is_admin, '1');
    }
}
