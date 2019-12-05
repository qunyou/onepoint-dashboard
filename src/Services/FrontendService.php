<?php

namespace App\Services;

use App\Repositories\SettingRepository;
use App\Repositories\NaviRepository;

class FrontendService
{
    /**
     * 取得設定
     */
    public static function putSetting($model, $append_query_array = [])
    {
        $tpl_data = [];
        $setting_repository = new SettingRepository;
        $setting = $setting_repository->getSetting($model, $append_query_array);
        if ($setting) {
            foreach ($setting as $value) {
                $tpl_data[$value->setting_key] = $value->setting_value;
            }
        }
        return $tpl_data;
    }

    /**
     * 主導覽
     */
    public static function putNavi($navi_category_id = 0, $navi_style = [])
    {
        $navi_repository = new NaviRepository;

        // 載入不同的主導覽產生方法
        if (config('frontend.navi_method', false)) {
            return $navi_repository->{config('frontend.navi_method')}($navi_category_id, $navi_style);
        }
        return false;
    }
}
