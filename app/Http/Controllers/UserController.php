<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Validator;


class UserController extends Controller
{
    public function showProfileForm(Request $request)
    {
        // TODO: stub
        return view('home.user_profile');
    }

    /**
     * 受け取った情報をもとにユーザー情報を更新する
     */
    public function editProfile(Request $request)
    {
        $profile = $request->only('name');
        $validator = Validator::make($profile, [
            'name' => 'required',
            ]
        );
        if ( $validator->fails() ) {
            return $this->showProfileForm($request);
        }

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
