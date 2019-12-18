<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 相簿
 * php artisan make:migration create_albums_table --create=albums
 * php artisan make:migration create_album_pivots_table --create=album_pivots
 */
class Album extends Model
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

        // 封面圖片檔名
        'file_name',

        // 相簿標題
        'album_title',

        // 相簿標題slug
        'album_title_slug',

        // 相簿說明
        'album_content',
    ];

    // 分類關聯
    public function album_category()
    {
        return $this->belongsToMany('App\Entities\AlbumCategory', 'album_pivots');
    }

    // 相片關聯
    public function album_image()
    {
        return $this->belongsToMany('App\Entities\AlbumImage', 'album_image_pivots');
    }
}
