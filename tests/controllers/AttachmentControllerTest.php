<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User;
use App\Article;
use App\Attachment;


class AttachmentControllerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        DB::transaction(function ()
        {
            User::create(['name'=>'test_user_1', 'email' => 'user1@example.com', 'password' => '']);
            User::create(['name'=>'test_user_2', 'email' => 'user2@example.com', 'password' => '']);
        });
        $this->be(User::find(1));
    }


    protected function addMoreFixture()
    {
        DB::transaction(function ()
        {
            Article::create(['title' => 'test title', 'body' => 'test', 'status' => 'draft', 'author_id' => 2,]);
            Attachment::create(['original_name' => 'foo', 'file_name' => 'foo', 'mime_type' => 'text/plain', 'article_id' => 1, 'owner_id' => 2]);
            Attachment::create(['original_name' => 'bar', 'file_name' => 'bar', 'mime_type' => 'text/plain', 'article_id' => 1, 'owner_id' => 2]);
        });
    }

    public function testIndex()
    {
        $this->addMoreFixture();
        $this->be(User::find(2));
        $response = $this->action('GET', 'AttachmentController@index', ['articleId' => 1], [], [], []);
        $this->assertEquals($response->getStatusCode(), 200);
    }

    public function testIndexNotParams()
    {
        $this->addMoreFixture();
        $response = $this->action('GET', 'AttachmentController@index', [], [], [], []);
        $this->assertEquals($response->getStatusCode(), 400);

    }

    public function testIndexArticleExist()
    {
        $this->addMoreFixture();
        $response = $this->action('GET', 'AttachmentController@index', ['articleId' => 2], [], [], []);
        $this->assertEquals($response->getStatusCode(), 400);

    }

    public function testIndexArticleNotOwned()
    {
        $this->addMoreFixture();
        $response = $this->action('GET', 'AttachmentController@index', ['articleId' => 1], [], [], []);
        $this->assertEquals($response->getStatusCode(), 400);
    }

    /**
     *
     * @return void
     */
    public function testStoreInvalid()
    {
        $response = $this->action('POST', 'AttachmentController@store', [], [], [], []);
        $this->assertEquals($response->getStatusCode(), 400);
    }

    /**
     *
     * @return void
     */
    public function testStoreValid()
    {
        $uploadedFile = new Symfony\Component\HttpFoundation\File\UploadedFile(
            __FILE__,
            'AttachmentControllerTest.php',
            'text/plain',
            null,
            null,
            true
        );
        $response = $this->action('POST', 'AttachmentController@store', [], [], [], ['attachment' => $uploadedFile]);
        $this->assertEquals($response->getStatusCode(), 200);
        $respJson = json_decode($response->getContent());
        $this->assertEquals(1, $respJson->data[0]->id);
        $this->assertEquals('AttachmentControllerTest.php', $respJson->data[0]->original_name);
        $this->assertRegExp('/\/storage\/.*\.php/', $respJson->data[0]->url);
    }
}
