<?php

namespace Onepoint\Dashboard\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Image;

class FileService
{
    /**
     * 取得檔案類型
     *
     * $input_name     String       表單名稱
     *
     * @return String
     */
    public static function getType($input_name)
    {
        $has_file = request()->hasFile($input_name);
        if ($has_file) {
            $file_request = request()->file($input_name);
            return $file_request->getMimeType();
        }
        return false;
    }

    /**
     * 判斷是否為圖檔
     *
     * $input_name     String       表單名稱
     *
     * @return boolean
     */
    public static function isImage($input_name)
    {
        if (substr(FileService::getType($input_name), 0, 5) == 'image') {
            return true;
        }
        return false;
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
        $file_request = request()->file($input_name);
        
        // 判斷是否多檔上傳
        if (is_array($file_request)) {
            foreach ($file_request as $value) {
                $arr[] = FileService::exeUpload($value, $prefix, $size_limit, $resize, $folder);
            }
            return $arr;
        } else {
            return FileService::exeUpload($input_name, $prefix, $size_limit, $resize, $folder);
        }
    }

    /**
    * 執行上傳
    *
    * $input_name     String      表單名稱或 file request 物件
    * $prefix         String      上傳後檔案的檔名前綴
    * $size_limit     String      限制上傳大小，單位為 bytes
    * $resize         String      是否縮圖
    * $folder         String      自訂上傳路徑
    *
    * @return Array ['origin_file_name' => '原始檔名', 'origin_name' => '不含副檔名原始檔名', 'file_extention' => '副檔名', 'file_name', 'file_size']
    */
    public static function exeUpload($input_name, $prefix = '', $size_limit = 0, $resize = true, $folder = '')
    {
        if (is_string($input_name)) {
            if (request()->hasFile($input_name)) {
                $file_request = request()->file($input_name);
            } else {
                return false;
            }
        } else {
            $file_request = $input_name;
        }
        
        $file_extention = $file_request->getClientOriginalExtension();
        $real_path = $file_request->getRealPath();
        $file_name = $prefix . '-' . Str::random(8) . '.' . $file_extention;
        $origin_file_name = $file_request->getClientOriginalName();
        $origin_name_arr = explode('.', $origin_file_name);
        $origin_name = $origin_name_arr[0];
        $file_size = $file_request->getClientSize();

        // 原始檔案路徑
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
            // $save_path = public_path('storage/' . $upload_path . '/' . $file_name);
            $save_path = storage_path('app/public/' . $upload_path . '/' . $file_name);
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
                // $save_path = public_path('storage/' . $thumb_path . '/' . $file_name);
                $save_path = storage_path('app/public/' . $thumb_path . '/' . $file_name);
                $img->resize($value['width'], null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($save_path);
            }
        } else {
            $file_request->storeAs('public/' . $upload_path, $file_name);
        }
        return compact('origin_file_name', 'origin_name', 'file_extention', 'file_name', 'file_size');
    }

    /**
     * 刪除檔案
     *
     * $input_name     String      檔名
     *
     * @return Array or false
     */
    public static function delete($file_name)
    {
        if (!empty($file_name)) {
            if (Storage::disk('public')->exists(config('frontend.upload_path') . '/' . $file_name)) {
                $path = config('frontend.upload_path') . '/' . $file_name;

                // 執行刪除
                Storage::disk('public')->delete($path);
            }
        }
    }

    /**
     * 顯示檔案大小
     *
     * @return String
     */
    public static function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }
        return $bytes;
    }

    /**
     * 檔案是否存在
     *
     * @return Boolean
     */
    public static function exists($file_name, $path = '')
    {
        if (!empty($path)) {
            $path = $path . '/';
        }
        $file_path = config('frontend.upload_path') . '/' . $path . $file_name;
        if (Storage::disk('public')->exists($file_path)) {
            return true;
        }
        return false;
    }

    /**
     * 下載檔案
     *
     * @return Response
     */
    public static function download($file_name, $display_name, $path = '')
    {
        if (!empty($path)) {
            $path = $path . '/';
        }
        $file_path = 'storage/' . config('frontend.upload_path') . '/' . $path . $file_name;
        return response()->download($file_path, $display_name);
    }
}
