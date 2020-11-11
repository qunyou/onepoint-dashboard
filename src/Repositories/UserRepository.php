<?php

namespace Onepoint\Dashboard\Repositories;

use Hash;
use Illuminate\Validation\Rule;
use Onepoint\Dashboard\Repositories\BaseRepository;
use Onepoint\Dashboard\Entities\User;
use Onepoint\Dashboard\Entities\RoleUser;

/**
 * 人員
 */
class UserRepository extends BaseRepository
{
    /**
     * 建構子
     */
    function __construct()
    {
        parent::__construct(new User);
    }

    /**
     * 權限
     */
    public function permissions()
    {
        return $this->model;
    }

    /**
     * 列表
     */
    public function getList($id = 0, $paginate = 0)
    {
        $query = $this->permissions()->with('roles');
        return $this->fetchList($query, $id, $paginate, 'created_at', 'desc');
    }

    /**
     * 單筆資料查詢
     */
    public function getOne($id)
    {
        $query = $this->permissions()->where('id', $id)->with('roles')->first();
        if (!is_null($query)) {
            return $query;
        }
        return false;
    }

    /**
     * 更新，包含 role 資料
     */
    public function setUpdate($id = 0, $datas = [])
    {
        $role_id = request('role_id', false);
        $datas['password'] = request('password', '');

        // 表單驗證
        $rule_array = [
            'realname' => [
                'required',
                'max:255'
            ],
            'username' => [
                'required',
                'max:255',
                Rule::unique('users')->ignore($id)->whereNull('deleted_at'),
            ],
            'email' => [
                'required',
                'max:255',
                Rule::unique('users')->ignore($id)->whereNull('deleted_at'),
                'email'
            ]
        ];
        if ($id == 0) {
            $rule_array['password'] = [
                'required',
                'max:255',
                'confirmed',
                'min:6',
                'alpha_dash'
            ];
        } else {
            if (!blank($datas['password'])) {
                $rule_array['password'] = [
                    'max:255',
                    'confirmed',
                    'min:6',
                    'alpha_dash'
                ];
            }
        }
        $custom_name_array = [
            'realname' => __('auth.姓名'),
            'username' => __('auth.帳號'),
            'email' => __('auth.Email'),
            'password' => __('auth.密碼'),
        ];
        if (!$id) {
            $datas['password'] = Hash::make($datas['password']);
            if (!isset($datas['sort'])) {
                $datas['sort'] = User::count() + 1;
            }
        } else {
            if (blank($datas['password'])) {
                unset($datas['password']);
            } else {
                $datas['password'] = Hash::make($datas['password']);
            }

            // 先刪除已指派的群組
            RoleUser::where('user_id', $id)->delete();
        }
        if ($id) {
            $result = $this->rule($rule_array, $custom_name_array)->exclude(['password'])->append($datas)->replicateUpdate($id);
        } else {
            $result = $this->rule($rule_array, $custom_name_array)->exclude(['password'])->append($datas)->update();
        }
        if ($result) {

            // 指派群組
            if ($role_id = request('role_id', false)) {
                $user = User::find($result);
                $user->roles()->attach($role_id);
            }
            return $result;
        }
        return false;
    }

    /**
     * 選單值
     */
    public function getOptionItem()
    {
        $arr = [0 => '未設定'];
        $query = $this->permissions()->orderBy('sort')->where('id', '!=', session('auth.user.id'))->get();
        if ($query->count()) {
            foreach ($query as $value) {
                $arr[$value->id] = $value->realname . '(' . $value->username . ')';
            }
        }
        return $arr;
    }
}
