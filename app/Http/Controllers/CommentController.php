<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Article;
use App\Comment;

use Auth;

class CommentController extends Controller
{
    //
    
    public function postOne(Request $request, $articleId)
    {
        $user = Auth::user();
        $comment = new Comment([
            'body' => $request->input('body'),
        ]);
        $comment->user_id = $user->id;
        $article = Article::find($articleId);
        $article->comments()->save($comment);
        return redirect('/articles/'.$articleId);
    }
}
