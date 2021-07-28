<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{

    /**
     * Show the login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('admin.auth_login', [
            'title' => 'Admin Login',
            'loginRoute' => 'login',
        ]);
    }

    public function logout()
    {
        Auth::guard('web')->logout();
        return redirect()
            ->route('login')
            ->with('status', 'Admin has been logged out!');
    }

    public function login(Request $request)
    {
        $this->validator($request);

        if (Auth::guard('web')->attempt($request->only('email', 'password'))) {
            return redirect()
                ->intended(route('admin.dashboard'))
                ->with('status', '登入成功');
        }

        //Authentication failed...
        return $this->loginFailed();
    }

    private function validator(Request $request)
    {
        //validation rules.
        $rules = [
            'email'    => 'required|email|exists:users|min:5|max:191',
            'password' => 'required|string|min:6|max:255',
            /*'captcha' => 'required|captcha',*/
        ];

        //custom validation error messages.
        $messages = [
            'email.required' => '請輸入登入帳號',
            'email,email' => '請輸入正確帳號格式（Email）',
            'email.exists' => '無此登入帳號.',
            'password.required' => '請輸入登入密碼',
            'password.min' => '密碼長度至少6的字元',
            /*'captcha.required' => '請輸入驗證碼',
            'captcha.captcha' => '驗證碼錯誤',*/
        ];

        //validate the request.
        $request->validate($rules, $messages);
    }


    private function loginFailed()
    {
        return redirect()
            ->back()
            ->withInput()
            ->with('error', '登入失敗！請重新輸入帳號密碼.');
    }

    protected function redirectTo()
    {
        route('admin.dashboard');
    }
}
