<?php

namespace Onepoint\Dashboard\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 網站設定
 */
class Setting extends Model
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

        // ALTER TABLE `settings` ADD `update_user_id` INT NOT NULL DEFAULT '0' AFTER `origin_id`;
        // 記錄更新人員
        'update_user_id',

        // 狀態 enum('啟用', '停用')
        'status',

        // 排序
        'sort',

        // 給哪個功能的設定
        'model',

        // 設定類別
        // 'type' => [
        //     'number',
        //     'text',
        //     'textarea',
        //     'editor',
        //     'file_name',
        //     'color',
        // ],
        'type',

        // 標題
        'title',

        // 說明
        'description',

        // 設定索引
        'setting_key',

        // 設定值
        'setting_value',
    ];
}
