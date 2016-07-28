<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Storage;


class AttachmentController extends Controller
{
    //
    public function store(Request $request)
    {
        $file = $request->file('attachment');
        if ( !$request->hasFile('attachment') ) {
            abort(400);
        }
        Storage::disk('local')->put($file->getClientOriginalName(), file_get_contents($file->getRealPath()));

        return 'OK';
    }
}
