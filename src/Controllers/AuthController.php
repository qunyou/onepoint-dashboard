<?php

namespace Onepoint\Dashboard\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Hash;
// use App\Entities\User;
// use App\Presenters\PathPresenter;
// use App\Repositories\UserRepository;
// use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class AuthController extends Controller
{
    // use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('guest');
        // view 路徑
        $this->tpl_data = [];
        $this->view_path = config('backend.view_path') . '.pages.dashboard.';
    }

    /**
     * 登入頁
     */
    public function login()
    {
        // PathPresenter $path_presenter
        if (auth()->check()) {
            return redirect(config('backend.uri') . '/' . config('backend.login_default_uri', 'dashboard/index'));
        }
        // $tpl_data['path_presenter'] = $path_presenter;
        // return view($path_presenter->backend_view('login'), $tpl_data);
        return view($this->view_path . 'login', $this->tpl_data);
    }

    /**
     * 登出
     */
    public function logout()
    {
        Auth::logout();
        return redirect(config('backend.uri') . '/auth/login');
    }

    /**
     * 登入
     */
    public function postLogin()
    {
        $credentials = request()->only('username', 'password');
        $credentials['status'] = '啟用';
        $remember = false;
        if (request('remember', false) == 'remember-me') {
            $remember = true;
        }
        if (Auth::attempt($credentials, $remember)) {
            return redirect(config('backend.uri') . '/' . config('backend.login_default_uri', 'dashboard/index'));
        }
        session()->flash('login_message', '帳號密碼錯誤');
        return redirect(config('backend.uri') . '/auth/login');
    }

    /**
     * 忘記密碼
     */
    public function reset(PathPresenter $path_presenter)
    {
        $tpl_data['path_presenter'] = $path_presenter;
        return view($path_presenter->backend_view('email'), $tpl_data);
    }
}