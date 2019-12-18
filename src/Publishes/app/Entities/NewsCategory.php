<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 新聞分類
 * php artisan make:migration create_news_categories_table --create=news_categories
 */
class NewsCategory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'created_at',
        'updated_at',
        'deleted_at',

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

    // 新聞關聯
    public function news()
    {
        return $this->belongsToMany('App\Entities\News', 'news_pivots');
    }
}
