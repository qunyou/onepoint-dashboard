<?php

namespace App\Repositories;

use Illuminate\Validation\Rule;
use Onepoint\Dashboard\Repositories\BaseRepository;
use Onepoint\Dashboard\Services\BaseService;
use App\Entities\Resource;

/**
 * 資源
 */
class ResourceRepository extends BaseRepository
{
    /**
     * 建構子
     */
    function __construct()
    {
        parent::__construct(new Resource);
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
        $query = $this->permissions()->with('resource_category');
        if ($resource_category_id = request('resource_category_id', 0)) {
            $query = $query->whereHas('resource_category', function ($q) use ($resource_category_id) {
                $q->where('resource_category_id', $resource_category_id);
            });
        }
        return $this->fetchList($query, $id, $paginate);
    }

    /**
     * 單筆資料查詢
     */
    public function getOne($id)
    {
        $query = $this->permissions()->with('resource_category');
        return $this->fetchOne($query, $id);
    }

    /**
     * 更新
     */
    public function setUpdate($id = 0, $datas = [])
    {
        // 表單驗證
        $rule_array = [
            'resource_title' => [
                'required',
                'max:255',
                Rule::unique('resources')->ignore($id)->whereNull('deleted_at'),
            ]
        ];
        $custom_name_array = [
            'resource_title_slug' => __('backend.標題')
        ];
        if ($id) {
            $result = $this->rule($rule_array, $custom_name_array)->append($datas)->replicateUpdate($id);
        } else {
            $result = $this->rule($rule_array, $custom_name_array)->append($datas)->update();
        }
        if ($result) {

            // 處理複選資料
            $resource_category_id = request('resource_category_id', []);
            $sync_datas = [];
            foreach ($resource_category_id as $category_id) {
                $sync_datas[] = $category_id;
            }
            $this->model->find($result)->resource_category()->sync($sync_datas);
            return $result;
        } else {
            return false;
        }
    }
}
