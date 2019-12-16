<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 相簿相片
 * php artisan make:migration create_album_images_table --create=album_images
 * php artisan make:migration create_album_image_pivots_table --create=album_image_pivots
 */
class AlbumImage extends Model
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

        // 點擊
        'click',

        // 圖片標題
        'album_images_title',

        // 圖片檔名
        'file_name',

        // 相片說明
        'album_images_content',
    ];


    // 相簿關聯
    public function album()
    {
        return $this->belongsToMany('App\Entities\Album', 'album_image_pivots');
    }
}
