<?php
namespace App\Services;

use Str;
use Hash;
use Auth;
use App\Repositories\MemberRepository;

class MemberService
{
    /**
     * 建構子
     */
    function __construct()
    {
        $this->member_repository = new MemberRepository;
    }

    /**
     * 登入檢查
     */
    public function check()
    {
        if (auth()->guard('member')->check()) {
            $res = $this->member_repository->frontendGetById(auth()->guard('member')->id());
            if ($res) {
                return $res;
            } else {
                $this->logout();
            }
        } else {
            $this->logout();
        }
        return false;
    }

    /**
     * 登入
     */
    // public function login($query)
    // {
    //     session(['member.id' => $query->id]);
    //     session(['member.realname' => $query->realname]);
    // }

    /**
     * 登出
     */
    public function logout()
    {
        Auth::guard('member')->logout();
    }

    /**
     * 以 id 登入會員
     */
    public function loginById($id)
    {
        $member = $this->member_repository->frontendGetById($id);
        if ($member) {
            Auth::guard('member')->login($member);
        } else {
            Auth::guard('member')->logout();
        }
    }

    /**
     * 確認 email 是否已存在
     */
    public function hasEmail($email)
    {
        return $this->member_repository->hasEmail($email);
    }

    /**
     * 自動加入會員
     */
    public function addAuto($datas)
    {
        $datas['password'] = Hash::make(Str::random(8));
        $datas['member_type'] = '暫時';
        $id = $this->member_repository->update(0, $datas);
        return $id;
    }

    /**
     * 確認是否為正式會員
     *
     * return false 代表非正式會員
     */
    public function typeCheck($email)
    {
        $query = $this->member_repository->model->where('email', $email)->where('member_type', '正式')->first();
        if (!is_null($query)) {
            return $query->id;
        }
        return false;
    }
}
