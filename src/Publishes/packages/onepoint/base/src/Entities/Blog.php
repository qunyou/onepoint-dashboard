<?php

namespace Onepoint\Base\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Blog
 * php artisan make:migration create_blogs_table --create=blogs
 * php artisan make:migration create_blog_pivots_table --create=blog_pivots
 */
class Blog extends Model
{
    use SoftDeletes;

    // protected $table = 'news';

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

        // ALTER TABLE `awards` ADD `html_title` VARCHAR(255) NULL DEFAULT NULL AFTER `language`, ADD `meta_keywords` VARCHAR(255) NULL DEFAULT NULL AFTER `html_title`, ADD `meta_description` VARCHAR(255) NULL DEFAULT NULL AFTER `meta_keywords`;
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
        'blog_title',

        // 文章標題slug
        'blog_title_slug',

        // 簡短說明
        'summary',

        // 文章內容
        'blog_content',
    ];

    // 更新人員關聯
    public function user()
    {
        return $this->belongsTo('Onepoint\Base\Entities\User');
    }

    // 分類關聯
    public function blog_category()
    {
        return $this->belongsToMany('Onepoint\Base\Entities\BlogCategory', 'blog_pivots');
    }
}
