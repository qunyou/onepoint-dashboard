<?php

namespace Onepoint\Dashboard\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Image;

class ImageService
{
    /**
     * 取得縮圖
     *
     * @return string
     */
    public static function thumb($file_name, $attribute = '', $default_str = '', $custom_folder = '')
    {
        return Self::showImg('thumb/', $file_name, $attribute, $default_str, $custom_folder);
    }

    /**
     * 取得中型尺寸
     *
     * @return string
     */
    public static function normal($file_name, $attribute = '', $default_str = '', $custom_folder = '')
    {
        return Self::showImg('normal/', $file_name, $attribute, $default_str, $custom_folder);
    }

    /**
     * 取得大型尺寸
     *
     * @return string
     */
    public static function large($file_name, $attribute = '', $default_str = '', $custom_folder = '')
    {
        return Self::showImg('large/', $file_name, $attribute, $default_str, $custom_folder);
    }

    /**
     * 取得原圖
     *
     * @return string
     */
    public static function origin($file_name, $attribute = '', $default_str = '', $custom_folder = '')
    {
        return Self::showImg('', $file_name, $attribute, $default_str, $custom_folder);
    }

    /**
     * 圖片路徑
     *
     * $path            String      縮圖路徑
     * $file_name       String      檔名
     * $custom_folder   String      資料夾
     *
     * @return string
     */
    public static function getPath($path, $file_name, $custom_folder = '')
    {
        $custom_path = '';
        if (!empty($custom_folder)) {
            $custom_path = $custom_folder;
        }
        $custom_path = $custom_path . '/' . $path;
        $file_path = config('frontend.upload_path') . '/' . $custom_path . $file_name;
        if (!empty($file_name)) {
            if (Storage::disk('public')->exists($file_path)) {
                return asset('storage/' . $file_path);
            }
        }
        return false;
    }

    /**
     * 製作圖片 html code
     *
     * @return string
     */
    public static function showImg($path, $file_name, $attribute = '', $default_str, $custom_folder = '')
    {
        if (!empty($file_name)) {
            $file_path = Self::getPath($path, $file_name, $custom_folder);
            if ($file_path = Self::getPath($path, $file_name, $custom_folder)) {
                if (is_array($attribute)) {
                    $attribute = join(' ', array_map(function($key) use ($attribute) {
                        if(is_bool($attribute[$key])) {
                            return $attribute[$key]?$key:'';
                        }
                        return $key.'="'.$attribute[$key].'"';
                    }, array_keys($attribute)));
                }
                return '<img src="' . $file_path . '" ' . $attribute . '>';
            }
        }
        if ($default_str === false) {
            return $file_name;
        }
        if (!empty($default_str)) {
            return $default_str;
        }
        if (config('frontend.upload_image_default_string', false)) {
            return config('frontend.upload_image_default_string');
        }
        return '';
    }

    /**
     * 上傳檔案
     *
     * $input_name     String      表單名稱
     * $prefix         String      上傳後檔案的檔名前綴
     * $size_limit     String      限制上傳大小，單位為 bytes
     * $resize         String      是否縮圖
     * $folder         String      自訂上傳路徑
     *
     * @return Array or false
     */
    public static function upload($input_name, $prefix = '', $size_limit = 0, $resize = true, $folder = '')
    {
        return FileService::exeUpload($input_name, $prefix, $size_limit, $resize, $folder);
    }

    /**
     * 刪除檔案
     *
     * $input_name     String      檔名
     * $folder         String      自訂資料夾
     *
     * @return Array or false
     */
    public static function delete($file_name, $resize = true, $folder = '')
    {
        if (!empty($file_name)) {
            $upload_path = config('frontend.upload_path');
            if (!empty($folder)) {
                $upload_path .= '/' . $folder;
            }
            $file_path = $upload_path . '/' . $file_name;
            if (Storage::disk('public')->has($file_path)) {

                // 原始圖路徑
                $path[] = $file_path;

                // 縮圖路徑
                if ($resize) {
                    $image_scale_setting = config('backend.image_scale_setting');
                    foreach ($image_scale_setting as $value) {
                        $path[] = $upload_path . '/' . $value['path'] . '/' . $file_name;
                    }
                }

                // 執行刪除
                Storage::disk('public')->delete($path);
            }
        }
    }
}
