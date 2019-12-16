<?php

namespace App\Repositories;

use Illuminate\Validation\Rule;
use Onepoint\Dashboard\Repositories\BaseRepository;
use Onepoint\Dashboard\Services\BaseService;
use App\Entities\Album;

/**
 * 文章
 */
class AlbumRepository extends BaseRepository
{
    /**
     * 建構子
     */
    function __construct()
    {
        parent::__construct(new Album);
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
        $query = $this->permissions()->with('album_category')->withCount('album_image');
        if ($album_category_id = request('album_category_id', 0)) {
            $query = $query->whereHas('album_category', function ($q) use ($album_category_id) {
                $q->where('album_category_id', $album_category_id);
            });
        }
        return $this->fetchList($query, $id, $paginate);
    }

    /**
     * 單筆資料查詢
     */
    public function getOne($id)
    {
        $query = $this->permissions()->with('album_category');
        return $this->fetchOne($query, $id);
    }

    /**
     * 更新
     */
    public function setUpdate($id = 0, $datas = [])
    {
        $this->upload_file_form_name = 'file_name';
        $this->upload_file_name_prefix = 'album';
        $this->upload_file_folder = 'album';

        // 表單驗證
        $rule_array = [
            'album_title' => [
                'required',
                'max:255',
                Rule::unique('albums')->ignore($id)->whereNull('deleted_at'),
            ]
        ];
        $custom_name_array = [
            'album_title' => __('backend.標題')
        ];
        $datas['album_title_slug'] = BaseService::slug(request('album_title'), '-');
        if ($id) {
            $result = $this->rule($rule_array, $custom_name_array)->append($datas)->replicateUpdate($id);
        } else {
            $result = $this->rule($rule_array, $custom_name_array)->append($datas)->update();
        }
        if ($result) {
            
            // 處理複選資料
            $album_category_id = request('album_category_id', []);
            $sync_datas = [];
            foreach ($album_category_id as $category_id) {
                $sync_datas[] = $category_id;
            }
            $this->model->find($result)->album_category()->sync($sync_datas);
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
        
        // 建立預設相簿
        if ($query->count() == 0) {
            $res = $this->model->create(['status' => '啟用', 'album_title' => '預設相簿', 'sort' => 1, 'note' => '系統自動建立']);
            $query = $this->permissions()->where('status', '啟用')->orderBy('sort')->get();
        }
        foreach ($query as $value) {
            $arr[$value->id] = $value->album_title;
        }
        return $arr;
    }
}
