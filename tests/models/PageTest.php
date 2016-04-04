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
            Page::create(['headline' => 'test1!', 'title' => 'test title 1', 'body' => 'test text 1', 'location' => 'header']);
            Page::create(['headline' => 'test2!', 'title' => 'test title 2', 'body' => 'test text 2', 'location' => 'footer']);
        });
    }

    public function testFetchHeaderPages()
    {
        $pages = Page::fetchHeaderPages();
        $this->assertEquals(count($pages), 1);
        $this->assertEquals($pages[0]->id, 1);
        $this->assertEquals($pages[0]->headline, 'test1!');
    }

    public function testFetchFooterPages()
    {
        $pages = Page::fetchFooterPages();
        $this->assertEquals(count($pages), 1);
        $this->assertEquals($pages[0]->id, 2);
        $this->assertEquals($pages[0]->headline, 'test2!');
    }
}
