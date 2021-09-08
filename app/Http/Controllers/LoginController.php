<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class LoginController extends Controller
{
    public function home ()
    {
        if (!is_null($this->user())) return redirect()->route('admin');
        return view('adminLogin');
    }

    public function login (Request $req)
    {
        $data = $req->all();

        $this->validate($req, [
            'username' => 'required',
            'password' => 'required',
            'login_as'  => ['required', Rule::in(['admin', 'official'])]
        ]);

        $guard = $data['login_as'];

        if (Auth::guard($guard)->attempt(['username' => $data['username'], 'password' => $data['password']])) {
            $user = Auth::guard($guard)->user();

            if ($user->active >= 1) {
                Auth::guard($guard)->login($user);
                return redirect()->route('admin');

            } else {
                return back()
                    ->withInput($req->only('username', 'login_as'))
                    ->with('errors', ["This account is deactivated."]);
            }
            
        } else {
            return back()
                ->withInput($req->only('username', 'login_as'))
                ->with('errors', ["Username or Password is incorrect."]);
        }
    }

    public function logout (Request $req) {
        Session::flush();
        Auth::guard('admin')->logout();
        Auth::guard('official')->logout();
        return redirect('/login');
    }
}
