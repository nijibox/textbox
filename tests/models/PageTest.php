<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use \App\Page;


class PageTest extends \TestCase
{
    public function setUp()
    {
        parent::setUp();
        DB::transaction(function ()
        {
            Page::create(['headline' => 'test!', 'title' => 'test title', 'body' => 'test text']);
        });
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }
}
