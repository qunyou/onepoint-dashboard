<?php

namespace Onepoint\Dashboard\Services;

// use Illuminate\Http\Request;
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
     * 製作圖片 html code
     *
     * @return string
     */
    public static function showImg($path, $file_name, $attribute = '', $default_str, $custom_folder = '')
    {
        if (!empty($file_name)) {
            $custom_path = '';
            if (!empty($custom_folder)) {
                $custom_path = $custom_folder;
            }
            if (!empty($path)) {
                $custom_path = $custom_path . '/' . $path;
            }
            // $file_path = 'storage/' . config('frontend.upload_path') . '/' . $path . $file_name;
            $file_path = config('frontend.upload_path') . '/' . $custom_path . $file_name;
            if (Storage::disk('public')->exists($file_path)) {
                if (is_array($attribute)) {
                    $attribute = join(' ', array_map(function($key) use ($attribute) {
                        if(is_bool($attribute[$key])) {
                            return $attribute[$key]?$key:'';
                        }
                        return $key.'="'.$attribute[$key].'"';
                    }, array_keys($attribute)));
                }
                return '<img src="' . asset('storage/' . $file_path) . '" ' . $attribute . '>';
            }
        }
        if ($default_str === false) {
            return '';
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
        if (request()->hasFile($input_name)) {
            $file_request = request()->file($input_name);
            $file_extention = $file_request->getClientOriginalExtension();
            $real_path = $file_request->getRealPath();
            $file_name = $prefix . '-' . Str::random(8) . '.' . $file_extention;
            $origin_file_name = $file_request->getClientOriginalName();
            $origin_name_arr = explode('.', $origin_file_name);
            $origin_name = $origin_name_arr[0];
            $file_size = $file_request->getClientSize();

            // 原始圖路徑
            $upload_path = config('frontend.upload_path');
            if (!empty($folder)) {
                $upload_path .= '/' . $folder;
            }

            // 建立資料夾
            $exists = Storage::disk('public')->exists($upload_path);
            if (!$exists) {
                Storage::disk('public')->makeDirectory($upload_path);
            }

            // 製作縮圖
            if ($resize && $file_extention != 'svg') {

                // 上傳準備
                $img = Image::make($real_path);
                if ($size_limit > 0) {
                    $size = $img->filesize();
                    if ($size > $size_limit) {
                        return false;
                    }
                }

                // 上傳原始圖
                $save_path = public_path('storage/' . $upload_path . '/' . $file_name);
                $img = $img->save($save_path);
                
                // 縮圖路徑
                $image_scale_setting = config('backend.image_scale_setting');
                foreach ($image_scale_setting as $value) {

                    // 指定資料夾
                    if (!empty($value['path'])) {
                        $thumb_path = $upload_path . '/' . $value['path'];

                        // 建立資料夾
                        $exists = Storage::disk('public')->exists($thumb_path);
                        if (!$exists) {
                            Storage::disk('public')->makeDirectory($thumb_path);
                        }
                    }
                    $save_path = public_path('storage/' . $thumb_path . '/' . $file_name);
                    $img->resize($value['width'], null, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($save_path);
                }
            } else {
                $file_request->storeAs('public/' . $upload_path, $file_name);
            }
            return compact('origin_file_name', 'origin_name', 'file_name', 'file_size');
        }
        return false;
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
