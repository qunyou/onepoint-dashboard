<?php

namespace Onepoint\Base\Repositories;

use Onepoint\Base\Entities\ArticleAttachment;
use Onepoint\Dashboard\Repositories\BaseRepository;

/**
 * 文章附檔
 * 1.0.01
 * packages/onepoint/base/src/Repositories/ArticleAttachmentRepository.php
 */
class ArticleAttachmentRepository extends BaseRepository
{
    /**
     * 建構子
     */
    public function __construct()
    {
        parent::__construct(new ArticleAttachment);
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
        $this->upload_origin_file_column_name = 'attachment_title';
        $this->upload_file_size_column_name = 'file_size';
        $this->upload_file_origin_column_name = 'origin_name';
        $this->upload_file_extention_column_name = 'file_extention';
        $this->upload_file_resize = false;
        $this->upload_file_name_prefix = 'article';
        $this->upload_file_folder = 'article';
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

    /**
     * 前端相片列表
     */
    public function getFrontendList($product_title, $paginate = false)
    {
        // 查詢商品
        $product = Product::where('product_title_slug', $product_title)->first();

        // 查詢相片
        $query = $this->model->with('article');
        if (!is_null($product)) {

            // 增加相簿點擊
            $product->increment('click');
            $product_id = $product->id;
            $query = $query->whereHas('product', function ($q) use ($product_id) {
                $q->where('product_id', $product_id);
            });
        }
        if (!$paginate) {
            $paginate = config('frontend.paginate');
        }
        return $this->fetchList($query, 0, $paginate);
    }
}
