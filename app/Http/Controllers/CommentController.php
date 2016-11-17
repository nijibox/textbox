<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Article;
use App\Comment;

use Auth;
use DB;

class CommentController extends Controller
{
    //
    
    public function postOne(Request $request, $articleId)
    {
        $user = Auth::user();
        $article = Article::find($articleId);
        if ( is_null($article) ) {
            abort(404);
        }
        if ( $article->status == 'draft' ) {
            abort(400);
        }

        $comment = new Comment([
            'body' => $request->input('articleComment'),
        ]);
        $comment->user_id = $user->id;
        DB::transaction(function () use ($article, $comment, $request)
        {
            $request->session()->flash('flash_message', 'コメントしました');
            $article->comments()->save($comment);
        });
        
        return redirect('/articles/'.$articleId.'#comment'.$comment->id);
    }
}
