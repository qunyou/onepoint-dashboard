<?php

namespace Onepoint\Base\Repositories;

use Illuminate\Validation\Rule;
use Onepoint\Dashboard\Services\BaseService;
use Onepoint\Dashboard\Repositories\BaseRepository;
use Onepoint\Base\Entities\News;

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
     * 更新
     */
    public function setUpdate($id = 0, $datas = [])
    {
        $this->upload_file_form_name = ['file_name', 'file_name_sm'];
        $this->upload_file_name_prefix = 'slider';
        $this->upload_file_folder = 'slider';

        // 表單驗證
        $rule_array = [
            // 'slider_name' => [
            'title' => [
                'required',
                'max:255'
            ]
        ];
        $custom_name_array = [
            // 'slider_name' => __('backend.圖片名稱')
            'title' => __('base::slider.輪播圖名稱')
        ];
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
     * 前端資料
     */
    public function getFrontendList($limit = false)
    {
        $query = $this->permissions()->where('status', '啟用')->orderBy('sort');
        if ($limit) {
            $query = $query->limit($limit);
        }
        $query = $query->get();
        if ($query->count()) {
            return $query;
        }
        return false;
    }

    /**
     * 以分類編號查詢列表(前端)
     */
    public function getListByCategoryId($news_category_id = 0, $paginate_limit = 0)
    {
        // 查詢新聞
        $query = $this->permissions()
            ->with('news_category')
            ->where('status', '啟用')
            ->where('public_forever', '啟用')
            ->orderBy('sort')->orderBy('post_at', 'desc');
        if ($news_category_id > 0) {
            $query->whereHas('news_category', function ($q) use ($news_category_id) {
                $q->where('status', '啟用')->orderBy('sort');
                if ($news_category_id > 0) {
                    $q->where('news_category_id', $news_category_id);
                }
            });
        }
        $query = $query->orWhere(function($q) {
            $q->where('public_forever', '停用')->where('public_start_at', '<=', config('site.today'))->where('public_end_at', '>=', config('site.today'));
        });
        if (!empty($this->select)) {
            $query = $query->select($this->select);
        }
        if ($this->limit > 0) {
            $query = $query->limit($this->limit)->get();
        } else {
            if ($paginate_limit == 0) {
                $query = $query->get();
            } else {
                $query = $query->paginate($paginate_limit);
            }
        }
        if ($query->count()) {
            return $query;
        }
        return false;
    }
}
