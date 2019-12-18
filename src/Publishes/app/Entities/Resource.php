<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 資源
 * php artisan make:migration create_resources_table --create=resources
 * php artisan make:migration create_resources_pivots_table --create=resource_pivots
 */
class Resource extends Model
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

        // 連結網址
        'resource_url',

        // 連結標題
        'resource_title',

        // 連結開啟方法 enum('預設分頁', '另開分頁')
        'resource_target',
    ];

    // 分類關聯
    public function resource_category()
    {
        return $this->belongsToMany('App\Entities\ResourceCategory', 'resource_pivots');
    }
}
