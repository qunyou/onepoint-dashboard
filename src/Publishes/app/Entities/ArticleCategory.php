<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 文章分類
 * php artisan make:migration create_article_categories_table --create=article_categories
 */
class ArticleCategory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        // 'created_at',
        // 'updated_at',
        // 'deleted_at',

        // 舊版本
        'old_version',

        // 版本原始id
        'origin_id',

        //狀態 enum('啟用', '停用')
        'status',

        // 排序
        'sort',

        // 備註
        'note',

        // 類別名稱
        'category_name',

        // 類別名稱slug
        'category_name_slug',
    ];

    // 文章關聯
    public function article()
    {
        return $this->belongsToMany('App\Entities\Article', 'article_pivots');
    }
}
