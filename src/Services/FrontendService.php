<?php

namespace Onepoint\Dashboard\Services;

use Onepoint\Dashboard\Repositories\SettingRepository;
use Onepoint\Base\Repositories\NaviRepository;

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
        // if (config('frontend.navi_method', false)) {
            // return $navi_repository->{config('frontend.navi_method')}($navi_category_id, $navi_style);
            return $navi_repository->getNavi($navi_category_id, $navi_style);
        // }
        // return false;
    }

    /**
     * 設定 head 值
     */
    public static function getHead($model_detail)
    {
        $tpl_data = [];
        if ($model_detail) {
            if (!blank($model_detail->html_title)) {
                $tpl_data['html_title'] = $model_detail->html_title;
            }
            if (!blank($model_detail->html_title)) {
                $tpl_data['meta_keywords'] = $model_detail->meta_keywords;
            }
            if (!blank($model_detail->html_title)) {
                $tpl_data['meta_description'] = $model_detail->meta_description;
            }
        }
        return $tpl_data;
    }
}
