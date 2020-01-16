<?php

namespace Onepoint\Dashboard\Presenters;

/**
 * 路徑輔助方法
 */
class PathPresenter
{
    /**
     * 前台資產路徑
     */
    static function assets($file_path)
    {
        return url(config('frontend.assets_frontend_path') . $file_path);
    }

    /**
     * 後台資產路徑
     */
    static function backend_assets($file_path)
    {
        return url(config('dashboard.assets_dashboard_path') . $file_path);
    }

    /**
     * 前台view路徑
     */
    static function view($view_path)
    {
        return config('frontend.view_frontend_path') . '.' . $view_path;
    }

    /**
     * 後台view路徑
     */
    static function backend_view($view_path)
    {
        return config('dashboard.view_path') . '.' . $view_path;
    }

    /**
     * 上傳檔案路徑
     */
    static function upload($upload_path = '')
    {
        if (!empty($upload_path)) {
            return url('storage/' . config('frontend.upload_path') . '/' . $upload_path);
        } else {
            return url('storage/' . config('frontend.upload_path'));
        }
    }

    /**
     * 判斷網址，並加上 active 字串
     */
    static function matches($pattern, $active_string = ' class="active"')
    {
        // 可印出目前網址路徑
        // Request::path()
        // 可用萬用字元判斷網址
        // Request::is('event/detail/*');
        if (request()->is($pattern)) {
            return $active_string;
        }
        return '';
    }
}
