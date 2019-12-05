<?php

namespace App\Presenters;

/**
 * 文章輔助方法
 */
class ArticlePresenter
{
    /**
     * 判斷checkbox是否勾選
     */
    static function url($article_object)
    {
        if (empty($article_object->url)) {
            $url_str = url('article/detail/' . $article_object->title);
        } else {
            $url_str = $article_object->url;
        }
        return $url_str;
    }
}