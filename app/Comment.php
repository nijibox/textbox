<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Article;


/**
 * 記事コメント
 */
class Comment extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
