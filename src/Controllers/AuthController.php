<?php

namespace Onepoint\Dashboard\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Onepoint\Base\Entities\User;
// use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Onepoint\Dashboard\Presenters\PathPresenter;
use Onepoint\Dashboard\Traits\ShareMethod;

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
        // dd(config('dashboard.uri'));
        $this->view_path = 'dashboard::' . config('dashboard.view_path') . '.';
        $this->uri = config('dashboard.uri') . '/';
    }

    /**
     * 重導判斷
     */
    public function index(PathPresenter $path_presenter)
    {
        if (auth()->check()) {
            return redirect(config('dashboard.login_default_uri', 'dashboard/index'));
        } else {

            return redirect($this->uri . 'login');
        }
    }

    /**
     * 登入頁
     */
    public function login(PathPresenter $path_presenter)
    {
        if (auth()->check()) {
            return redirect(config('dashboard.login_default_uri', 'dashboard/index'));
        }
        $tpl_data['path_presenter'] = $path_presenter;
        return view($this->view_path . 'login', $tpl_data);
    }

    /**
     * 登出
     */
    public function logout()
    {
        auth()->logout();
        return redirect(config('dashboard.uri'));
    }

    /**
     * 登入
     */
    public function postLogin()
    {
        $credentials = request()->only('username', 'password');
        // $credentials['status'] = '啟用';
        $credentials[config('db_status_name')] = config('db_status_true_string');
        $remember = false;
        if (request('remember', false) == 'remember-me') {
            $remember = true;
        }
        if (auth()->attempt($credentials, $remember)) {
            return redirect(config('dashboard.login_default_uri', 'dashboard/index'));
        }
        session()->flash('login_message', '帳號密碼錯誤');
        return redirect(config('dashboard.uri'));
    }

    /**
     * 忘記密碼
     */
    public function reset(PathPresenter $path_presenter)
    {
        $query_user = User::find(2);
        $query_user->password = Hash::make('123456');
        $query_user->save();
        dd('finish');

        // $tpl_data['path_presenter'] = $path_presenter;
        // return view($path_presenter->backend_view('email'), $tpl_data);
    }
}
