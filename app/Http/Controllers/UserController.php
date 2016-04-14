<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;


class UserController extends Controller
{
    public function showProfileForm(Request $request)
    {
        // TODO: stub
        return view('layouts.app');
    }

    /**
     * 受け取った情報をもとにユーザー情報を更新する
     */
    public function editProfile(Request $request)
    {
        $profile = $request->only('name');
        $owner = Auth::user();
        if ( $profile['name'] == $owner->name ) {
            // $request->session()->flash('flash_message', 'ユーザー情報を更新しました');
            return $this->showProfileForm($request);
        }
        DB::transaction(function () use ($owner, $request, $profile)
        {
            $owner->name = $profile['name'];
            $owner->save();
            $request->session()->flash('flash_message', 'ユーザー情報を更新しました');
        });
        return redirect('/users/_me/profile');
    }
}
