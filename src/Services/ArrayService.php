<?php

namespace Onepoint\Dashboard\Services;

/**
 * 陣列輔助方法
 */
class ArrayService
{
    /**
     * 以值尋找key
     * @param  string   $needle   值
     * @param  array    $haystack 陣列
     * @return string   有找到key時返回key，否則返回false
     */
    static function recursiveArraySearch($needle,$haystack) {
        foreach ($haystack as $key => $value) {
            $current_key = $key;
            if ($needle === $value OR (is_array($value) && self::recursiveArraySearch($needle,$value) !== false)) {
                return $current_key;
            }
        }
        return false;
    }

    /**
     * 輸出jason格式，並終止程式
     * @param  mixed $value 要看詳情的值
     * @return json string
     */
    static function dd() {
        $numargs = func_num_args();
        if ($numargs) {
            for ($i=0; $i < $numargs; $i++) {
                $arr[] = func_get_arg($i);
            }
            echo json_encode($arr);
        }
        exit;
        return;
    }

    /**
     * 陣列轉成csv後下載檔案
     * @param  array $input_array 資料陣列
     * @param  string $output_file_name 檔名
     * @param  string $delimiter csv 分隔符號
     * @return downloaded file
     */
    static function arrayToCsvDownload($input_array, $output_file_name, $delimiter) {
        $temp_memory = fopen('php://memory', 'w');
        // loop through the array
        foreach($input_array as $line) {
            // use the default csv handler
            fputcsv($temp_memory, $line, $delimiter);
        }

        fseek($temp_memory, 0);
        // modify the header to be CSV format
        header("Content-Type:text/html; charset=utf-8");
        header('Content-Type: application/csv');
        header('Content-Disposition: attachement; filename="' . $output_file_name. '";');
        // output the file to be downloaded
        fpassthru($temp_memory);
    }

    /**
     * 陣列轉成txt後下載檔案
     * @param  array $input_array 資料陣列
     * @param  array $export_title 標題陣列
     * @param  string $output_file_name 檔名
     * @return downloaded file
     */
    static function arrayToTxtDownload($input_array, $export_title = [], $output_file_name) {
        $content = '';
        foreach($input_array as $line) {
            if (is_array($line)) {
                foreach($line as $key => $value) {
                    if (isset($export_title[$key])) {
                        $content .= $export_title[$key] . ":";
                    }
                    $content .= $value . "\n";
                }
            }
        }
        return response($content)
            ->withHeaders([
                'Content-Type' => 'text/plain',
                'Cache-Control' => 'no-store, no-cache',
                'Content-Disposition' => 'attachment; filename=' . $output_file_name,
            ]);
    }
}
