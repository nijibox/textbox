<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Ramsey\Uuid\Uuid;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Attachment;
use App\Article;

use Storage;
use Auth;
use DB;


class AttachmentController extends Controller
{
    public function index(Request $request)
    {
        $articleId = $request->input('articleId', null);
        // Currently, articleId is required.
        if ( is_null($articleId) ) {
            abort(400);
        }
        // Only article owner can get attachments
        $article = Article::find($articleId);
        if ( is_null($article) || $article->author_id != Auth::user()->id ) {
            abort(400);
        }
        return [
            'data' => $article->attachments,
        ];
    }

    //
    public function store(Request $request)
    {
        $file = $request->file('attachment');
        if ( !$request->hasFile('attachment') ) {
            abort(400);
        }
        
        $fileName = 'attachments/' . Uuid::uuid4() . '.'. $file->getClientOriginalExtension();
        // Store file
        Storage::disk()->put(
            $fileName,
            file_get_contents($file->getRealPath())
        );
        $result = Storage::url($fileName);

        // Manage as model
        $attachment = Attachment::create([
            'file_name' => $fileName,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getClientMimeType(),
            'owner_id' => Auth::user()->id,
        ]);
        DB::transaction(function () use ($attachment)
        {
            $attachment->save();
        });

        $data = [$attachment];
        return ['data' => $data]; 
    }
}
