<?php

namespace App\Repositories;

use Illuminate\Validation\Rule;
use Onepoint\Dashboard\Repositories\BaseRepository;
use Onepoint\Dashboard\Services\BaseService;
use App\Entities\Page;

/**
 * 單頁
 */
class PageRepository extends BaseRepository
{
    /**
     * 建構子
     */
    function __construct()
    {
        parent::__construct(new Page);
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
        return $this->fetchList($this->permissions(), $id, $paginate);
    }

    /**
     * 單筆資料查詢
     */
    public function getOne($id)
    {
        return $this->fetchOne($this->permissions(), $id);
    }

    /**
     * 更新
     */
    public function setUpdate($id = 0, $datas = [])
    {
        // 表單驗證
        $rule_array = [
            'page_title' => [
                'required',
                'max:255',
                Rule::unique('pages')->ignore($id)->whereNull('deleted_at'),
            ]
        ];
        $custom_name_array = [
            'page_title' => __('backend.標題')
        ];
        $datas['page_title_slug'] = BaseService::slug(request('page_title'), '-');
        if ($id) {
            $result = $this->rule($rule_array, $custom_name_array)->append($datas)->replicateUpdate($id);
        } else {
            $result = $this->rule($rule_array, $custom_name_array)->append($datas)->update();
        }
        if ($result) {
            return $result;
        } else {
            return false;
        }
    }
}
