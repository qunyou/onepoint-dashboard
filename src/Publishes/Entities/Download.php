<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 下載
 * php artisan make:migration create_downloads_table --create=downloads
 * php artisan make:migration create_downloads_pivots_table --create=download_pivots
 */
class Download extends Model
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

        // 發佈日期
        'post_at',

        // 檔名
        'file_name',

        // 檔案大小
        'file_size',

        // 標題
        'download_title',

        // 說明
        'download_description',
    ];

    // 分類關聯
    public function download_category()
    {
        return $this->belongsToMany('App\Entities\DownloadCategory', 'download_pivots');
    }
}
