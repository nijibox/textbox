<?php

use Illuminate\Support\Facades\DB;
use \App\User;
use \App\Article;
use Carbon\Carbon;


class ArticleTest extends \TestCase
{
    public function setUp()
    {
        parent::setUp();
        DB::transaction(function ()
        {
            User::create(['name'=>'test_user_1', 'email' => 'user1@example.com', 'password' => '']);
            User::create(['name'=>'test_user_2', 'email' => 'user2@example.com', 'password' => '']);
            $article = Article::create(
                ['title' => 'test title1', 'body' => 'test', 'status' => 'internal', 'author_id' => 1,]
            );
            $article->updated_at = Carbon::create(2015, 1, 2, 1, 0, 2);
            $article->save();
            $article = Article::create(
                ['title' => 'test title2', 'body' => 'test', 'status' => 'internal', 'author_id' => 2,]
            );
            $article->updated_at = Carbon::create(2015, 1, 2, 2, 0, 2);
            $article->save();
        });
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testLatestInternal()
    {
        $articles = Article::latestInternal()->get();
        $this->assertEquals(count($articles), 2);
        $this->assertEquals($articles->first()->title, 'test title2');
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testLatestAuthor()
    {
        $articles = Article::latestAuthor(User::find(1))->get();
        $this->assertEquals(count($articles), 1);
        $this->assertEquals($articles->first()->title, 'test title1');
    }

    public function testTagsRelation()
    {
        $article = Article::find(1);
        $this->assertEquals(count($article->tags), 0);
    }
}
