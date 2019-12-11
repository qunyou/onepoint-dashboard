<?php

namespace Onepoint\Dashboard\Presenters;

use Onepoint\Dashboard\Services\ImageService;
use Onepoint\Dashboard\Services\StringService;

/**
 * 設定輔助方法
 */
class SettingPresenter
{
    /**
     * 判斷checkbox是否勾選
     */
    static function settingValueDisplay($setting_object)
    {
        switch ($setting_object->type) {
            case 'file_name':
                $str = ImageService::origin($setting_object->setting_value);
                break;

            case 'text':
            case 'editor':
                $str = StringService::htmlLimit($setting_object->setting_value, 20, '...');
                break;

            default:
                $str = StringService::htmlLimit($setting_object->setting_value, 20, '...');
                break;
        }
        return $str;
    }
}
