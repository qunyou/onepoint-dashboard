<?php

namespace Onepoint\Base\Repositories;

use Illuminate\Validation\Rule;
use Onepoint\Dashboard\Services\BaseService;
use Onepoint\Dashboard\Repositories\BaseRepository;
use Onepoint\Base\Entities\BlogCategory;

/**
 * Blog
 */
class BlogCategoryRepository extends BaseRepository
{
    /**
     * 建構子
     */
    function __construct()
    {
        parent::__construct(new BlogCategory);
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
        $query = $this->permissions()->withCount('blog');
        return $this->fetchList($query, $id, $paginate);
    }

    /**
     * 單筆資料查詢
     */
    public function getOne($id)
    {
        $query = $this->permissions()->with('blog');
        return $this->fetchOne($query, $id);
    }

    /**
     * 更新
     */
    public function setUpdate($id = 0, $datas = [])
    {
        $this->upload_file_form_name = 'file_name';
        $this->upload_file_name_prefix = 'blog';
        $this->upload_file_folder = 'blog';
        
        // 表單驗證
        $rule_array = [
            'category_name' => [
                'required',
                'max:255',
                Rule::unique('blog_categories')->ignore($id)->whereNull('deleted_at'),
            ]
        ];
        $custom_name_array = [
            'category_name' => __('backend.標題')
        ];
        $datas['category_name_slug'] = BaseService::slug(request('category_name'), '-');
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

    /**
     * 選單用資料查詢
     */
    public function getOptionItem()
    {
        // $arr = ['0' => '未設定'];
        $query = $this->permissions()->where('status', '啟用')->orderBy('sort')->get();
        
        // 建立預設分類
        if ($query->count() == 0) {
            $res = $this->model->create(['status' => '啟用', 'category_name' => '預設分類', 'category_name_slug' => '預設分類', 'sort' => 1, 'note' => '系統自動建立']);
            $query = $this->permissions()->where('status', '啟用')->orderBy('sort')->get();
        }
        foreach ($query as $value) {
            $arr[$value->id] = $value->category_name;
        }
        return $arr;
    }

    /**
     * 前端資料
     */
    public function getFrontendList()
    {
        // ->where('status', '啟用')
        $query = $this->permissions()
            ->orderBy('sort')
            ->withCount(['blog' => function ($q) {
                // ->where('status', '啟用')
                $q->orderBy('sort');
            }])->get();
        if ($query->count()) {
            return $query;
        }
        return false;
    }

    /**
     * 以名稱查詢單筆資料(前端)
     */
    public function getOneByName($category_name = '')
    {
        $query = $this->model->where('status', '啟用');
        if (!empty($category_name)) {
            if (is_numeric($category_name)) {
                $query = $query->find($category_name);
            } else {
                $query = $query->where('category_name_slug', $category_name)->first();
            }
            if (!is_null($query)) {
                return $query;
            }
        }
        return false;
    }
}
