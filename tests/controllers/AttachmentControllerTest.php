<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User;
use App\Attachment;

class AttachmentControllerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        DB::transaction(function ()
        {
            User::create(['name'=>'test_user_1', 'email' => 'user1@example.com', 'password' => '']);
        });
        $this->be(User::find(1));
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

        $attachment = Attachment::find(1);
        $this->assertEquals($attachment->path, '/storage/AttachmentControllerTest.php');
    }
}
