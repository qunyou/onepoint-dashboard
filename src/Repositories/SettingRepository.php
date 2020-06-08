<?php

namespace App\Repositories;

use Illuminate\Validation\Rule;
use Onepoint\Dashboard\Repositories\BaseRepository;
use App\Entities\Setting;

/**
 * 網站設定
 */
class SettingRepository extends BaseRepository
{
    /**
     * 建構子
     */
    function __construct()
    {
        parent::__construct(new Setting);
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
    public function getList($trashed = false, $model)
    {
        if (!$trashed) {
            $query = $this->permissions();
        } else {
            $query = $this->permissions()->onlyTrashed();
        }
        if (!empty($model)) {
            $query = $query->where('model', $model);
        }
        $query = $query->orderBy('sort')->paginate(config('site.paginate'));
        $append_array = [];
        if (!empty($model)) {
            $append_array['model'] = $model;
        }
        if ($query->count()) {
            if ($trashed) {
                $append_array['trashed'] = 'true';
            }
            if (count($append_array)) {
                $query->appends($append_array);
            }
            return $query;
        }
        return false;
    }

    /**
     * 列表
     */
    public function getRootList($id = 0, $paginate = 0)
    {
        $query = $this->permissions()->orderBy('sort');
        return $this->fetchList($query, $id, $paginate);
    }

    /**
     * 單筆資料查詢
     */
    public function getOne($id)
    {
        $query = $this->permissions()->where('id', $id)->first();
        if (!is_null($query)) {
            return $query;
        }
        return false;
    }

    /**
     * 取得設定值
     */
    public function getSetting($model, $append_query_array = [])
    {
        $query = $this->permissions();
        if (is_array($model)) {
            $query = $query->where('status', '啟用')->whereIn('model', $model);
        } else {
            if (!empty($model)) {
                $query = $query->where('status', '啟用')->where('model', $model);
            }
        }
        if (count($append_query_array)) {
            foreach ($append_query_array as $column_name => $column_value) {
                $query = $query->where($column_name, $column_value);
            }
        }
        $query = $query->get();
        if (!is_null($query)) {
            return $query;
        }
        return 0;
    }

    /**
     * 更新
     */
    public function setUpdate($id = 0, $datas = [])
    {
        $this->upload_file_form_name = 'setting_value';
        $this->upload_file_name_prefix = 'setting';
        $this->upload_file_folder = 'setting';
        // $this->upload_file_resize = false;

        // 表單驗證
        // $rule_array = [
        //     'setting_key' => [
        //         'required',
        //         'max:255',
        //         Rule::unique('settings')->ignore($id)->whereNull('deleted_at'),
        //     ]
        // ];
        // $custom_name_array = [
        //     'setting_key' => __('backend.標題')
        // ];
        // ->rule($rule_array, $custom_name_array)
        if ($id) {
            $result = $this->replicateUpdate($id);
        } else {
            $result = $this->update();
        }
        if ($result) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * 單筆資料查詢
     */
    public function getSettingValue($setting_key)
    {
        $query = $this->model->where('setting_key', $setting_key)->first();
        if (!is_null($query)) {
            return $query->setting_value;
        }
        return false;
    }
}
