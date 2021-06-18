<?php

namespace Onepoint\Base\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 最新消息
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

        // 記錄更新人員
        'update_user_id',

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

        // 文章標題
        'news_title',

        // 文章標題slug
        'news_title_slug',

        // 簡短說明
        'summary',

        // 文章內容
        'news_content',

        // 開始日期
        'start_at',

        // 結束日期
        'end_at',
    ];

    // 更新人員關聯
    public function user()
    {
        return $this->belongsTo('Onepoint\Base\Entities\User');
    }

    // 分類關聯
    public function news_category()
    {
        return $this->belongsToMany('Onepoint\Base\Entities\NewsCategory', 'news_pivots');
    }
}