<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ChangePasswordController extends Controller
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function index($id)
    {
        $user = $this->user->findOrFail($id);

        return view('users.changePassword')->with('user', $user);
    }

    public function changePassword(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'currentPassword' => 'required',
            'newPassword' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = $this->user->findOrFail($id);

        if (!Hash::check($request->input('currentPassword'), $user->password)) {
            return redirect()->back()
                ->withErrors(['currentPassword' => 'The current password is incorrect.'])
                ->withInput();
        }

        $user->password = Hash::make($request->input('newPassword'));
        $user->save();

        $details = [
            'name' => $user->name,
            'app_url' => config('app.url')
        ];

        Mail::send('users.emails.chg_pwd_conf', $details, function ($message) use($user) {
            $message->from(config('mail.from.address'), config('app.name'))
                ->to($user->email, $user->name)
                ->subject('Password Change Confirmation');
        });

        return redirect()->route('index');
    }
}
