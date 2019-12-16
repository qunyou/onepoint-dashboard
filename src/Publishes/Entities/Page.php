<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 單頁
 * php artisan make:migration create_pages_table --create=pages
 */
class Page extends Model
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

        // 點擊
        'click',

        // 單頁標題
        'page_title',

        // 單頁標題slug
        'page_title_slug',

        // 單頁內容
        'page_content',
    ];
}
