<?php

namespace Onepoint\Dashboard\Repositories;

use Illuminate\Validation\Rule;
use Onepoint\Dashboard\Repositories\BaseRepository;
use Onepoint\Dashboard\Entities\Role;

/**
 * 群組
 */
class RoleRepository extends BaseRepository
{
    /**
     * 建構子
     */
    function __construct()
    {
        parent::__construct(new Role);
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
    function getList($id = 0, $paginate = 0)
    {
        $query = $this->permissions();
        return $this->fetchList($query, $id, $paginate);
    }

    /**
     * 單筆資料查詢
     */
    public function getOne($id)
    {
        $query = $this->permissions();
        return $this->fetchOne($query, $id);
    }

    /**
     * 查詢權限
     */
    public function getPermissions($id)
    {
        $query = Role::find($id)->permissions()->get();
        if ($query->count()) {
            return $query;
        }
        return false;
    }

    /**
     * 更新
     */
    public function setUpdate($role_id = 0, $datas = [])
    {
        // 表單驗證
        $rule_array = [
            'role_name' => [
                'required',
                'max:255',
                Rule::unique($this->model->getTable())->ignore($role_id)->whereNull('deleted_at'),
            ]
        ];
        $custom_name_array = [
            'role_name' => __('dashboard::auth.人員群組'),
        ];

        // 將接收的資料設定為集合
        $collection = collect(request()->all());
        $role_datas['role_name'] = $collection->get('role_name');
        // $role_datas['role_slug'] = Str::slug($collection->get('role_name'), '-');

        // 清除不必要的資料
        $collection->forget('_token');
        $collection->forget('_method');
        $collection->forget('role_name');
        $collection->forget('role_id');

        // 處理勾選項目
        $arr = [];
        foreach ($collection as $controller_name => $permission_array) {
            if (is_array($permission_array)) {
                // if (isset($permission_array['update']) && $permission_array['update'] == 1) {
                // }
                foreach ($permission_array as $permission_key => $permission_value) {
                    if ($permission_value == 'update') {
                        if (isset($permission_array['update']) && $permission_array['update'] == 1) {
                            $arr[$permission_key . '-' . $controller_name] = true;
                        } else {
                            $arr[$permission_key . '-' . $controller_name] = false;
                        }
                    } else {
                        if ($permission_value == 1) {
                            $arr[$permission_key . '-' . $controller_name] = true;
                        } else {
                            $arr[$permission_key . '-' . $controller_name] = false;
                        }
                    }
                }
            }
        }
        $role_datas['permissions'] = $arr;
        if ($role_id) {
            // $this->append($role_datas)->replicateUpdate($role_id);
            $role_id = $this->rule($rule_array, $custom_name_array)->append($datas)->replicateUpdate($role_id);
        } else {
            // $role_id = $this->model->create($role_datas)->id;
            $role_id = $this->rule($rule_array, $custom_name_array)->append($datas)->update();
        }
        return $role_id;
    }

    /**
     * 選單用資料查詢
     */
    public function getOptionItem()
    {
        // $arr = ['0' => '未設定'];
        $query = $this->permissions()->whereStatus('啟用')->whereOldVersion(0)->orderBy('sort')->get();
        
        // 建立預設分類
        if ($query->count() == 0) {
            $res = $this->model->create(['status' => '啟用', 'role_name' => '預設群組', 'sort' => 1, 'note' => '系統自動建立']);
            $query = $this->permissions()->where('status', '啟用')->orderBy('sort')->get();
            
        }
        foreach ($query as $value) {
            $arr[$value->id] = $value->role_name;
        }
        return $arr;
    }
}
