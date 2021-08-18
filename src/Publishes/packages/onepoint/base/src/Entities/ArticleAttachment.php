<?php

namespace Onepoint\Base\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 文章附檔
 * 1.0.01
 * packages/onepoint/base/src/Entities/ArticleAttachment.php
 * php artisan make:migration create_article_attachments_table --create=article_attachments
 */
class ArticleAttachment extends Model
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

        // 檔名
        'file_name',

        // 檔案大小
        'file_size',

        // 副檔名
        'file_extention',

        // 原始檔名(不含副檔名)，下載檔案時檔名改成這個名稱
        'origin_name',

        // 原始完整檔名
        // 'origin_file_name',

        // 附檔標題
        'attachment_title',

        // 附檔說明
        'attachment_description',
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
