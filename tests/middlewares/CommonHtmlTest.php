<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\Middleware\CommonHtml;
use App\Page;

class CommonHtmlTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->view = app('view');
        $this->middleware = new CommonHtml($this->view);
        $this->request = new Request();
        DB::transaction(function ()
        {
            Page::create(['headline' => 'test1!', 'title' => 'test title 1', 'body' => 'test text 1', 'location' => 'header']);
            Page::create(['headline' => 'test2!', 'title' => 'test title 2', 'body' => 'test text 2', 'location' => 'footer']);
        });
    }

    /**
     */
    public function testHandle()
    {
        $this->middleware->handle($this->request, function($request){});
        $this->assertEquals(count($this->view->shared('headerPages')), 1);
        $this->assertEquals(count($this->view->shared('footerPages')), 1);
    }
}
