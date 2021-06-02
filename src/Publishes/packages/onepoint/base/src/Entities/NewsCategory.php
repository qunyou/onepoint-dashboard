<?php

namespace Onepoint\Base\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 最新消息分類
 * php artisan make:migration create_news_categories_table --create=news_categories
 */
class NewsCategory extends Model
{
    use SoftDeletes;

    // protected $table = 'news_categories';

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
        'click',

        // 類別名稱
        'category_name',

        // 類別名稱slug
        'category_name_slug',
        
        // 分類說明
        'category_description',
    ];

    // 更新人員關聯
    public function user()
    {
        return $this->belongsTo('Onepoint\Base\Entities\User');
    }

    // 文章關聯
    public function news()
    {
        return $this->belongsToMany('Onepoint\Base\Entities\News', 'news_pivots');
    }
}
