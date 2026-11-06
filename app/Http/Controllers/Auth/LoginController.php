<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function showLoginForm()
    {
        $previousURL = url()->previous();
        $baseURL = url()->to('/');

        if ($previousURL != $baseURL . '/login') {
            session()->put('url.intended', $previousURL);
        }
        return view('auth.login');
    }

    protected function authenticated(Request $request, $user)
    {
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isInstructor()) {
            return redirect()->route('instructor.dashboard');
        } else {
            return redirect()->intended(route('student.dashboard'));
        }
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
