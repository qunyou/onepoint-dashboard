<?php

namespace App\Repositories;

use Illuminate\Validation\Rule;
use Onepoint\Dashboard\Repositories\BaseRepository;
use Onepoint\Dashboard\Services\BaseService;
use App\Entities\News;

/**
 * 新聞
 */
class NewsRepository extends BaseRepository
{
    /**
     * 建構子
     */
    function __construct()
    {
        parent::__construct(new News);
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
        $query = $this->permissions()->with('news_category');
        if ($news_category_id = request('news_category_id', 0)) {
            $query = $query->whereHas('news_category', function ($q) use ($news_category_id) {
                $q->where('news_category_id', $news_category_id);
            });
        }
        return $this->fetchList($query, $id, $paginate);
    }

    /**
     * 單筆資料查詢
     */
    public function getOne($id)
    {
        $query = $this->permissions()->with('news_category');
        return $this->fetchOne($query, $id);
    }

    /**
     * 更新
     */
    public function setUpdate($id = 0, $datas = [])
    {
        $this->upload_file_form_name = 'file_name';
        $this->upload_file_name_prefix = 'news';
        $this->upload_file_folder = 'news';

        // 表單驗證
        $rule_array = [
            'news_title' => [
                'required',
                'max:255',
                Rule::unique('news')->ignore($id)->whereNull('deleted_at'),
            ]
        ];
        $custom_name_array = [
            'news_title_slug' => __('backend.標題')
        ];
        $datas['news_title_slug'] = BaseService::slug(request('news_title_slug'), '-');
        if ($id) {
            $result = $this->rule($rule_array, $custom_name_array)->append($datas)->replicateUpdate($id);
        } else {
            $result = $this->rule($rule_array, $custom_name_array)->append($datas)->update();
        }
        if ($result) {

            // 處理複選資料
            $news_category_id = request('news_category_id', []);
            $sync_datas = [];
            foreach ($news_category_id as $category_id) {
                $sync_datas[] = $category_id;
            }
            $this->model->find($result)->news_category()->sync($sync_datas);
            return $result;
        } else {
            return false;
        }
    }
}
