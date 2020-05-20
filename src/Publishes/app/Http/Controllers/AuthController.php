<?php

namespace App\Http\Controllers;

// use Illuminate\Support\Facades\Auth;
use Hash;
// use App\Entities\User;
use Onepoint\Dashboard\Presenters\PathPresenter;
// use App\Repositories\UserRepository;
// use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use App\Traits\ShareMethod;

class AuthController extends Controller
{
    // use SendsPasswordResetEmails;
    use ShareMethod;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       // $this->middleware('guest');
       $this->share();
    }

    /**
     * 重導判斷
     */
    public function index(PathPresenter $path_presenter)
    {
        if (auth()->check()) {
            return redirect(config('dashboard.uri') . '/' . config('dashboard.login_default_uri', 'dashboard/index'));
        } else {
            return redirect(config('dashboard.uri') . '/login');
        }
    }

    /**
     * 登入頁
     */
    public function login(PathPresenter $path_presenter)
    {
        if (auth()->check()) {
            return redirect(config('dashboard.uri') . '/' . config('dashboard.login_default_uri', 'dashboard/index'));
        }
        $tpl_data['path_presenter'] = $path_presenter;
        return view($path_presenter->backend_view('login'), $tpl_data);
    }

    /**
     * 登出
     */
    public function logout()
    {
        auth()->logout();
        return redirect(config('dashboard.uri') . '/auth/login');
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
        if (auth()->attempt($credentials, $remember)) {
            return redirect(config('dashboard.uri') . '/' . config('dashboard.login_default_uri', 'dashboard/index'));
        }
        session()->flash('login_message', '帳號密碼錯誤');
        return redirect(config('dashboard.uri') . '/auth/login');
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