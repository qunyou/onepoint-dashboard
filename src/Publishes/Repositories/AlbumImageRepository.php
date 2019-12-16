<?php

namespace App\Repositories;

use Illuminate\Validation\Rule;
use Onepoint\Dashboard\Repositories\BaseRepository;
use Onepoint\Dashboard\Services\BaseService;
use App\Entities\AlbumImage;

/**
 * 文章
 */
class AlbumImageRepository extends BaseRepository
{
    /**
     * 建構子
     */
    function __construct()
    {
        parent::__construct(new AlbumImage);
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
        $query = $this->permissions()->with('album');
        if ($album_id = request('album_id', 0)) {
            $query = $query->whereHas('album', function ($q) use ($album_id) {
                $q->where('album_id', $album_id);
            });
        }
        return $this->fetchList($query, $id, $paginate);
    }

    /**
     * 單筆資料查詢
     */
    public function getOne($id)
    {
        $query = $this->permissions()->with('album');
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
            'album_images_title' => [
                'required',
                'max:255',
                Rule::unique('album_images')->ignore($id)->whereNull('deleted_at'),
            ]
        ];
        $custom_name_array = [
            'album_images_title' => __('backend.標題')
        ];
        if ($id) {
            $result = $this->rule($rule_array, $custom_name_array)->append($datas)->replicateUpdate($id);
        } else {
            $result = $this->rule($rule_array, $custom_name_array)->append($datas)->update();
        }
        if ($result) {
            
            // 處理複選資料
            $album_id = request('album_id', []);
            $sync_datas = [];
            foreach ($album_id as $category_id) {
                $sync_datas[] = $category_id;
            }
            $this->model->find($result)->album()->sync($sync_datas);
            return $result;
        } else {
            return false;
        }
    }
}
