<?php

use Symfony\Component\Console\Tester\CommandTester;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Console\Commands\AdminNewUser;


class AdminNewUserTest extends \TestCase
{
    public function setUp() {
        // $this->store = m::mock('App\Services\Store');
        $this->application = $this->createApplication();
        $this->command = new AdminNewUser();
        $this->command->setLaravel($this->application->make('Illuminate\Contracts\Foundation\Application'));
        $this->command->setApplication($this->application->make('Symfony\Component\Console\Application'));
        parent::setUp();
    }

    /**
     * 正常系
     *
     * @return void
     */
    public function testValid()
    {
        // print(111);
        $tester = new CommandTester($this->command);
        // var_dump($tester);
        $tester->execute([
            'email' => 'user1@example.com',
            'password' => 'Password',
        ]);
        $this->assertEquals(User::count(), 1);
        return $tester;
    }

    /**
     * すでに登録されているメールアドレス
     *
     * @return void
     */
    public function testDuplicatedUser()
    {
        // print(111);
        $tester = $this->testValid();
        // var_dump($tester);
        $tester->execute([
            'email' => 'user1@example.com',
            'password' => 'Password2',
        ]);
        $this->assertEquals(User::count(), 1);
        return $tester;
    }

}
