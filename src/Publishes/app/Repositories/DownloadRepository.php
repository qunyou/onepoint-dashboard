<?php

namespace App\Repositories;

use Illuminate\Validation\Rule;
use Onepoint\Dashboard\Repositories\BaseRepository;
use Onepoint\Dashboard\Services\BaseService;
use App\Entities\Download;

/**
 * 下載
 */
class DownloadRepository extends BaseRepository
{
    /**
     * 建構子
     */
    function __construct()
    {
        parent::__construct(new Download);
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
        $query = $this->permissions()->with('download_category');
        if ($download_category_id = request('download_category_id', 0)) {
            $query = $query->whereHas('download_category', function ($q) use ($download_category_id) {
                $q->where('download_category_id', $download_category_id);
            });
        }
        return $this->fetchList($query, $id, $paginate);
    }

    /**
     * 單筆資料查詢
     */
    public function getOne($id)
    {
        $query = $this->permissions()->with('download_category');
        return $this->fetchOne($query, $id);
    }

    /**
     * 更新
     */
    public function setUpdate($id = 0, $datas = [])
    {
        $this->upload_file_form_name = 'file_name';
        $this->upload_file_size_column_name = 'file_size';
        $this->upload_file_resize = false;
        $this->upload_file_name_prefix = 'download';
        $this->upload_file_folder = 'download';

        // 表單驗證
        $rule_array = [
            'download_title' => [
                'required',
                'max:255',
                Rule::unique('downloads')->ignore($id)->whereNull('deleted_at'),
            ]
        ];
        $custom_name_array = [
            'download_title_slug' => __('backend.標題')
        ];
        if ($id) {
            $result = $this->rule($rule_array, $custom_name_array)->append($datas)->replicateUpdate($id);
        } else {
            $result = $this->rule($rule_array, $custom_name_array)->append($datas)->update();
        }
        if ($result) {

            // 處理複選資料
            $download_category_id = request('download_category_id', []);
            $sync_datas = [];
            foreach ($download_category_id as $category_id) {
                $sync_datas[] = $category_id;
            }
            $this->model->find($result)->download_category()->sync($sync_datas);
            return $result;
        } else {
            return false;
        }
    }

    /**
     * 前端列表
     */
    public function getFrontendList($paginate = 0)
    {
        $query = $this->model->with('download_category');
        if ($download_category_id = request('download_category_id', 0)) {
            $query = $query->whereHas('download_category', function ($q) use ($download_category_id) {
                $q->where('download_category_id', $resource_category_id);
            });
        }
        return $this->fetchList($query, 0, $paginate);
    }
}
