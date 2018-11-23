<?php

namespace App\Http\Controllers\Auth;

use Auth;
use Hash;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        return view('user.show', compact('user'));
    }

    public function password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password'    => 'required',
            'new_password'        => 'required',
            'new_repeat_password' => 'required|same:new_password',
        ]);

        $user = Auth::user();
        $currentPassword = $user->password;

        $ensureCurrentPassword = $request->input('current_password');
        if ($validator->passes() && Hash::check($ensureCurrentPassword, $currentPassword)) {
            $user->password = Hash::make($request->input('new_password'));
            $user->save();
            session(['status' => __('mmex.updated')]);

            return view('user.show', compact('user'));
        } else {
            $validator->errors()->add('current_password', __('mmex.current-password-wrong'));

            return redirect()->back()->withErrors($validator);
        }
    }
}
