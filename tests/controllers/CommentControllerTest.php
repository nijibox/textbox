<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Faker\Factory as Faker;
use App\User;
use App\Article;


class CommentControllerTest extends \TestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setup();
        $faker = Faker::create('ja_JP');
        $user = User::create([
            'name' => $faker->name,
            'email' => $faker->unique()->email,
            'password' => $faker->password,
        ]);
        $article = Article::create([
            'title' => $faker->name,
            'body' => $faker->text(),
            'status' => 'internal',
            'author_id' => $user->id,
        ]);
    }

    public function testRedirectInPost()
    {
        $user = User::find(1);
        $this->actingAs($user)
            ->post('/articles/1/comments/_new', ['body'=>'test'])
            ->assertResponseStatus('302');
        $article = Article::find(1);
        $this->assertEquals($article->comments->count(), 1);
    }

    public function testCommentsInNotPost()
    {
        $user = User::find(1);
        $this->actingAs($user)
            ->post('/articles/2/comments/_new', ['body'=>'test'])
            ->assertResponseStatus('404');
    }
}
