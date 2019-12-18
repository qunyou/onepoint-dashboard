<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 新聞
 * php artisan make:migration create_news_table --create=news
 * php artisan make:migration create_news_pivots_table --create=news_pivots
 */
class News extends Model
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

        // 狀態 enum('啟用', '停用')
        'status',

        // 排序
        'sort',

        // 備註
        'note',

        // 網頁標題
        'html_title',

        // 關鍵字
        'meta_keywords',

        // 網頁敘述
        'meta_description',

        // 發佈日期
        'post_at',

        // 點擊
        'click',

        // 代表圖
        'file_name',

        // 新聞標題
        'news_title',

        // 新聞標題slug
        'news_title_slug',

        // 新聞內容
        'news_content',

        // 發佈時間-開始
        'public_start_at',

        // 發佈時間-結束
        'public_end_at',

        // 永久發佈 enum('啟用', '停用')
        'public_forever',
    ];

    // 分類關聯
    public function news_category()
    {
        return $this->belongsToMany('App\Entities\NewsCategory', 'news_pivots');
    }
}
