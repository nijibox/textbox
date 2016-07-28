<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User;

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
        echo(asset('storage/AttachmentController.php'));


        $response = $this->action('POST', 'AttachmentController@store', [], [], [], ['attachment' => $uploadedFile]);
        print($response);
        $this->assertEquals($response->getStatusCode(), 200);
    }
}
