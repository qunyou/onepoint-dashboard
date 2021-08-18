<?php

namespace Onepoint\Base\Repositories;

use Onepoint\Base\Entities\ArticleImage;
use Onepoint\Dashboard\Repositories\BaseRepository;

/**
 * 文章附圖
 * 1.0.01
 * packages/onepoint/base/src/Repositories/ArticleImageRepository.php
 */
class ArticleImageRepository extends BaseRepository
{
    /**
     * 建構子
     */
    public function __construct()
    {
        parent::__construct(new ArticleImage);
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
        $query = $this->permissions()->with('article');
        if ($article_id = request('article_id', 0)) {
            $query = $query->where('article_id', $article_id);
        }
        return $this->fetchList($query, $id, $paginate);
    }

    /**
     * 單筆資料查詢
     */
    public function getOne($id)
    {
        $query = $this->permissions()->with('article');
        return $this->fetchOne($query, $id);
    }

    /**
     * 更新
     */
    public function setUpdate($id = 0)
    {
        $this->upload_file_form_name = 'file_name';
        // $this->upload_file_size_column_name = 'file_size';
        $this->upload_file_resize = true;
        $this->upload_file_name_prefix = 'article';
        $this->upload_file_folder = 'article';
        // $this->upload_origin_file_column_name = 'origin_file_name';

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
}
