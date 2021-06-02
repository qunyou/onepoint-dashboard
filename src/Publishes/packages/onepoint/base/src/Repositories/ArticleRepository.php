<?php

namespace Onepoint\Base\Repositories;

use Illuminate\Validation\Rule;
use Onepoint\Dashboard\Services\BaseService;
use Onepoint\Dashboard\Repositories\BaseRepository;
use Onepoint\Base\Entities\Article;

/**
 * 文章
 */
class ArticleRepository extends BaseRepository
{
    /**
     * 建構子
     */
    function __construct()
    {
        parent::__construct(new Article);
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
        $query = $this->permissions()->with('article_category');
        if ($article_category_id = request('article_category_id', 0)) {
            $query = $query->whereHas('article_category', function ($q) use ($article_category_id) {
                $q->where('article_category_id', $article_category_id);
            });
        }
        return $this->fetchList($query, $id, $paginate);
    }

    /**
     * 單筆資料查詢
     */
    public function getOne($id)
    {
        $query = $this->permissions()->with('article_category');
        return $this->fetchOne($query, $id);
    }

    /**
     * 更新
     */
    public function setUpdate($id = 0, $datas = [])
    {
        $this->upload_file_form_name = 'file_name';
        $this->upload_file_name_prefix = 'article';
        $this->upload_file_folder = 'article';

        // 表單驗證
        $rule_array = [
            'article_title' => [
                'required',
                'max:255',
                Rule::unique('articles')->ignore($id)->whereNull('deleted_at'),
            ]
        ];
        $custom_name_array = [
            'article_title' => __('backend.標題')
        ];
        $datas['article_title_slug'] = BaseService::slug(request('article_title'), '-');
        if ($id) {
            $result = $this->rule($rule_array, $custom_name_array)->append($datas)->replicateUpdate($id);
        } else {
            $result = $this->rule($rule_array, $custom_name_array)->append($datas)->update();
        }
        if ($result) {
            
            // 處理複選資料
            $article_category_id = request('article_category_id', []);
            $sync_datas = [];
            foreach ($article_category_id as $category_id) {
                $sync_datas[] = $category_id;
            }
            $this->model->find($result)->article_category()->sync($sync_datas);
            return $result;
        } else {
            return false;
        }
    }

    /**
     * 前端列表
     */
    public function getFrontendList($category_name = '', $paginate = false)
    {
        // 分類增加點擊 todo
        // $query->increment('click');

        $query = $this->model->with('article_category');
        if (!blank($category_name)) {
            $query = $query->whereHas('article_category', function ($q) use ($category_name) {
                $q->where('category_name_slug', $category_name);
            });
        }
        if (!$paginate) {
            $paginate = config('frontend.paginate');
        }
        return $this->fetchList($query, 0, $paginate);
    }

    /**
     * 前端細節資料
     */
    public function getOneByTitle($article_title)
    {
        $query = $this->model->where('article_title_slug', $article_title)->with('article_category')->first();
        if ($query) {

            // 增加點擊
            $query->increment('click');
            return $query;
        } else {
            return false;
        }
    }
}
