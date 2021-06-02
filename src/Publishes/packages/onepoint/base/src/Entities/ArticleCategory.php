<?php

namespace Onepoint\Base\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 文章分類
 * php artisan make:migration create_article_categories_table --create=article_categories
 */
class ArticleCategory extends Model
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

        //狀態 enum('啟用', '停用')
        'status',

        // 排序
        'sort',

        // 備註
        'note',

        // 點擊
        // 'click',

        // 類別名稱
        'category_name',

        // 類別名稱slug
        'category_name_slug',
        
        // 背景圖
        // 'file_name',
        
        // 分類說明
        // 'category_description',
    ];

    // 更新人員關聯
    public function user()
    {
        return $this->belongsTo('Onepoint\Base\Entities\User');
    }

    // 文章關聯
    public function article()
    {
        return $this->belongsToMany('Onepoint\Base\Entities\Article', 'article_pivots');
    }
}
