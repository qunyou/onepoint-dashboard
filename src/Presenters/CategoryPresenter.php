<?php

namespace Onepoint\Dashboard\Presenters;

/**
 * 資料分類方法
 */
class CategoryPresenter
{
    /**
     * 印出逗號分隔分類連結
     */
    static function commaCategoryName($category_object, $url)
    {
        foreach ($category_object as $value) {
            $arr[] = '<a href="' . url($url . $value->category_name_slug) . '">' . $value->category_name . '</a>';
        }
        return implode(',', $arr);
    }
}