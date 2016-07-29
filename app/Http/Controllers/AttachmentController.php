<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Ramsey\Uuid\Uuid;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Attachment;

use Storage;
use Auth;
use DB;


class AttachmentController extends Controller
{
    public function index(Request $request)
    {
        $articleId = $request->input('articleId', null);
        if ( !is_null($articleId) ) {
            return [
                'data' => Attachment::where('article_id', $articleId)->get(),
            ];
        }
        return [];
   
    }

    //
    public function store(Request $request)
    {
        $file = $request->file('attachment');
        if ( !$request->hasFile('attachment') ) {
            abort(400);
        }
        
        $fileName = Uuid::uuid4() . '.'. $file->getClientOriginalExtension();
        // Store file
        Storage::disk('public')->put(
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
