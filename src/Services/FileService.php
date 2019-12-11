<?php

namespace Onepoint\Dashboard\Services;

// use Request;
// use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

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
     * $input_name     String       表單名稱
     * $prefix         String       上傳後檔案的檔名前綴
     * $file_name      String       自訂檔名
     * $folder         String     自訂上傳路徑
     *
     * @return Array or false       [origin_name=原始檔名, file_name=上傳後檔名]
     */
    public static function upload($input_name, $prefix = '', $file_name = '', $folder = '')
    {
        $file_request = request()->file($input_name);

        // 上傳檔案路徑
        $upload_path = config('frontend.upload_path');

        // 指定資料夾
        if (!empty($folder)) {
            $upload_path .= '/' . $folder;
        }

        // 判斷是否多檔上傳
        if (is_array($file_request)) {
            foreach ($file_request as $value) {
                $arr[] = FileService::exeUpload($value, $prefix, $file_name, $upload_path);
            }
            return $arr;
        } else {
            if (request()->hasFile($input_name)) {
                return FileService::exeUpload($file_request, $prefix, $file_name, $upload_path);
            } else {
                return false;
            }
        }
    }

    /**
     * 執行上傳
     *
     * $file_request   Object       file request
     * $prefix         String       上傳後檔案的檔名前綴
     * $file_name      String       自訂檔名
     * $upload_path    String       上傳路徑
     *
     * @return Array or false       [origin_name=原始檔名, file_name=上傳後檔名]
     */
    public static function exeUpload($file_request, $prefix, $file_name, $upload_path)
    {
        // $file_request = request()->file($input_name);
        $file_extention = $file_request->getClientOriginalExtension();
        $real_path = $file_request->getRealPath();
        $file_name = $prefix . '-' . Str::random(8) . '.' . $file_extention;
        $origin_file_name = $file_request->getClientOriginalName();
        $origin_name_arr = explode('.', $origin_file_name);
        $origin_name = $origin_name_arr[0];
        $file_size = $file_request->getClientSize();

        // 錯誤訊息
        $error_message = $file_request->getErrorMessage();

        // 建立資料夾
        // $exists = Storage::disk('public')->exists($upload_path);
        // if (!$exists) {
        //     Storage::disk('public')->makeDirectory($upload_path);
        // }

        // 製作檔名
        // $new_file_name = '';
        // if (!empty($file_name)) {

        //     // 自訂檔名
        //     if (!empty($prefix)) {
        //         $new_file_name .= $prefix . '-';
        //     }
        //     $new_file_name .= $file_name;
        //     $new_file_name .= '.' . $file_request->getClientOriginalExtension();
        //     $path = $file_request->storeAs($upload_path, $new_file_name);
        // } else {
        //     $new_file_name = '';
        //     $path = $file_request->store($upload_path);
        //     $new_file_name = basename($path);
        // }

        // // 上傳
        // return ['origin_name' => $origin_name_arr[0], 'file_name' => $new_file_name, 'file_size' => $new_file_size, 'path' => $path];

        $file_request->storeAs('public/' . $upload_path, $file_name);
        return compact('origin_file_name', 'origin_name', 'file_name', 'file_size');
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
        if (empty($path)) {
            $path = public_path() . '/' . config('frontend.upload_path') . '/';
        }
        return file_exists($path . $file_name);
    }

    /**
     * 下載檔案
     *
     * @return Response
     */
    public static function download($file_name, $display_name, $path = '')
    {
        if (empty($path)) {
            $path = public_path() . '/' . config('frontend.upload_path') . '/';
        }
        if (file_exists($path . $file_name)) {
            return response()->download($path . $file_name, $display_name);
        } else {
            abort(404);
        }
    }
}
