<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SessionTest extends \TestCase
{
    public function testCatchDefault()
    {
        $application = $this->createApplication();
        $this->assertEquals(config('session.lifetime'), 7*24*60);
    }

    public function testCatch()
    {
        putenv('SESSION_LIFETIME=1');
        $application = $this->createApplication();
        $this->assertEquals(config('session.lifetime'), 1);
    }
}
