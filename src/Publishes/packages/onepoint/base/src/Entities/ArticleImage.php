<?php

namespace Onepoint\Base\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 文章圖片
 * 1.0.01
 * packages/onepoint/base/src/Entities/ArticleImage.php
 * php artisan make:migration create_article_images_table --create=article_images
 */
class ArticleImage extends Model
{
    use SoftDeletes;

    protected $fillable = [
        // 'id',
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

        // 文章關聯
        'article_id',

        // 圖片標題
        'image_title',

        // 圖片檔名
        'file_name'
    ];

    // 更新人員關聯
    public function update_user()
    {
        return $this->belongsTo('Onepoint\Base\Entities\User', 'update_user_id');
    }

    // 文章關聯
    public function article()
    {
        return $this->belongsTo('Onepoint\Base\Entities\Article');
    }
}
