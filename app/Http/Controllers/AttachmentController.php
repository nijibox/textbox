<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Attachment;

use Storage;
use Auth;
use DB;


class AttachmentController extends Controller
{
    //
    public function store(Request $request)
    {
        $file = $request->file('attachment');
        if ( !$request->hasFile('attachment') ) {
            abort(400);
        }
        
        // Store file
        Storage::disk('public')->put(
            $file->getClientOriginalName(),
            file_get_contents($file->getRealPath())
        );
        $result = Storage::url($file->getClientOriginalName());

        // Manage as model
        $attachment = Attachment::create([
            'path' => $file->getClientOriginalName(),
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
