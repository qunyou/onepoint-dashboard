<?php
// 預計要加到後台，目前還沒加
namespace App\Services;

// use Cache;
use Sentinel;
use Session;
use Request;
use App\Services\RouteService;
use App\Presenters\PathPresenter;

class AuthService
{
    /**
     * 建構子
     */
    function __construct(PathPresenter $path_presenter)
    {
        $this->permissions = session('auth.user.permissions', false);

        // 閒置過久，session已失效
        if (!$this->permissions) {

            // 重新設定session
            $auth = Sentinel::check();
            Session::put('auth.user.id', $auth->id);
            Session::put('auth.user.email', $auth->email);
            Session::put('auth.user.realname', $auth->realname);
            Session::put('auth.user.username', $auth->username);
            $user = $auth->roles;
            $permissions_collection = collect();
            $role_collection = collect();
            foreach ($user as $key => $value) {
                if (count($value->permissions)) {
                    $permissions_collection = $permissions_collection->merge($value->permissions[0]);
                }
                $role_collection->push($value->name);
            }
            Session::put('auth.user.permissions', $permissions_collection);
            Session::put('auth.user.roles', $role_collection);
            $this->permissions = session('auth.user.permissions');
        }
        $this->roles = session('auth.user.roles', false);
    }
}
