<?php

namespace Onepoint\Dashboard\Presenters;

use Illuminate\Support\Facades\Storage;
use Onepoint\Dashboard\Services\ImageService;

/**
 * 表單輔助方法
 */
class FormPresenter
{
    /**
     * 設定表單用的變數
     */
    public function setValue($input_setting, $input_type = '')
    {
        $input_setting['hidden'] = $input_setting['hidden'] ?? false;
        $input_setting['input_only'] = $input_setting['input_only'] ?? false;
        // $input_setting['header_grid_class'] = $input_setting['header_grid_class'] ?? 'col-sm-2 col-form-label';
        $input_setting['input_grid_class'] = $input_setting['input_grid_class'] ?? 'col-sm-10';
        $input_setting['prepend_str'] = $input_setting['prepend_str'] ?? '';
        $input_setting['depend_str'] = $input_setting['depend_str'] ?? '';
        $input_setting['error'] = $input_setting['error'] ?? false;
        $input_setting['help'] = $input_setting['help'] ?? false;
        $input_setting['input_value'] = $input_setting['input_value'] ?? '';
        $input_setting['include_path'] = $input_setting['include_path'] ?? '';
        $input_setting['row_attribute'] = $input_setting['row_attribute'] ?? [];
        // $input_setting['parameter'] = $input_setting['parameter'] ?? [];
        
        // 表單尺寸
        $input_setting['input_size_class'] = $input_setting['input_size_class'] ?? '';
        // switch ($input_setting['input_size_class']) {
        //     case 'lg':
        //         $input_setting['header_grid_class'] .= ' col-form-label-lg';
        //         $input_setting['input_size_class'] = ' form-control-lg';
        //         break;
        //     case 'sm':
        //         $input_setting['header_grid_class'] .= ' col-form-label-sm';
        //         $input_setting['input_size_class'] = ' form-control-sm';
        //         break;
        // }

        // 其他表單屬性
        $input_setting['attribute'] = $input_setting['attribute'] ?? '';
        if (is_array($input_setting['attribute'])) {
            // $input_setting['attribute'] = str_replace("=", '="', http_build_query($input_setting['attribute'], null, '" ', PHP_QUERY_RFC3986)).'"';
            $attribute = $input_setting['attribute'];
            $input_setting['attribute'] = join(' ', array_map(function($key) use ($attribute) {
                if(is_bool($attribute[$key])) {
                    return $attribute[$key]?$key:'';
                }
                return $key.'="'.$attribute[$key].'"';
            }, array_keys($attribute)));
        }

        // 表單上層 row 屬性
        $input_setting['row_attribute'] = $input_setting['row_attribute'] ?? '';
        if (is_array($input_setting['row_attribute'])) {
            // $input_setting['attribute'] = str_replace("=", '="', http_build_query($input_setting['attribute'], null, '" ', PHP_QUERY_RFC3986)).'"';
            $attribute = $input_setting['row_attribute'];
            $input_setting['row_attribute'] = join(' ', array_map(function($key) use ($attribute) {
                if(is_bool($attribute[$key])) {
                    return $attribute[$key]?$key:'';
                }
                return $key.'="'.$attribute[$key].'"';
            }, array_keys($attribute)));
        }

        /**
         * 各種表單特定值
         */
        // select
        if ($input_type == 'select') {
            $input_setting['option'] = $input_setting['option'] ?? [];

            // 選單值使用 array key 或 array value
            $input_setting['use_array_value'] = $input_setting['use_array_value'] ?? false;
        }
        if ($input_type == 'select-vue') {
            $input_setting['option'] = $input_setting['option'] ?? [];
            $input_setting['item_key'] = $input_setting['item_key'] ?? '';
            $input_setting['parent_key'] = $input_setting['parent_key'] ?? '';

            // 選單值使用 array key 或 array value
            $input_setting['use_array_value'] = $input_setting['use_array_value'] ?? false;
        }

        // textarea
        if ($input_type == 'textarea') {
            $rows = $input_setting['rows'] ?? 3;

            // bootsrap 4 以前
            // $rows = 'rows="' . $rows . '"';

            // bootsrap 5
            $rows = 'style="height: ' . $rows. 'rem"';
            $input_setting['rows'] = $rows;
        }

        // radio
        if ($input_type == 'radio') {
            $input_setting['option'] = $input_setting['option'] ?? [];
        }

        // file
        if ($input_type == 'value' || $input_type == 'file') {
            $input_setting['upload_path'] = $input_setting['upload_path'] ?? '';
            // if (!empty($input_setting['upload_path'])) {
            //     $input_setting['upload_path'] .= '/';
            // }
            $input_setting['image_attribute'] = $input_setting['image_attribute'] ?? '';
            $input_setting['image_default_str'] = $input_setting['image_default_str'] ?? '';
            $input_setting['value_type'] = $input_setting['value_type'] ?? '';
            if (is_array($input_setting['image_attribute'])) {
                $attribute = $input_setting['image_attribute'];
                $input_setting['image_attribute'] = join(' ', array_map(function($key) use ($attribute) {
                    if(is_bool($attribute[$key])) {
                        return $attribute[$key]?$key:'';
                    }
                    return $key.'="'.$attribute[$key].'"';
                }, array_keys($attribute)));
            }
            $input_setting['image_thumb'] = $input_setting['image_thumb'] ?? false;
            $input_setting['image_string'] = '';
            
            // 檢查檔案是否存在
            if ($input_setting['value_type'] == 'image' || $input_setting['value_type'] == 'file' || $input_type == 'file') {
                $file_path = config('frontend.upload_path') . '/' . $input_setting['upload_path'] . '/' . $input_setting['input_value'];
                if (!empty($input_setting['input_value']) && Storage::disk('public')->has($file_path)) {

                    // 判斷檔案類型，決定顯示圖片或文字
                    switch (Storage::disk('public')->mimeType($file_path)) {
                        case 'image/jpeg':
                        case 'image/png':
                            if ($input_setting['image_thumb']) {
                                $input_setting['image_string'] = ImageService::thumb($input_setting['input_value'], $input_setting['image_attribute'], $input_setting['image_default_str'], $input_setting['upload_path']);
                            } else {
                                $input_setting['image_string'] = ImageService::origin($input_setting['input_value'], $input_setting['image_attribute'], $input_setting['image_default_str'], $input_setting['upload_path']);
                            }
                            break;
                    }
                    $input_setting['prepend_str'] = '<a href="' . asset('storage/' . $file_path) . '" target="_blank">';
                    $input_setting['depend_str'] = '</a>';

                    // 刪除附檔網址
                    if (empty($input_setting['delete_url'])) {
                        $input_setting['delete_url'] = url()->full() . '&delete_file=true&column=' . $input_setting['input_name'];
                    } else {
                        $input_setting['delete_url'] = $input_setting['delete_url'] . '&delete_file=true&column=' . $input_setting['input_name'];
                    }
                }
            }
        }
        if ($input_type == 'file') {
            $input_setting['multiple'] = $input_setting['multiple'] ?? false;
            
            // 限制上傳檔案類型
            // 副檔名
            // .csv
            // 逗號分隔多種副檔名
            // .csv,.xls
    
            // 網際網路媒體型式
            // image/*
            // text/html
            // video/*
            // audio/*
            // 逗號分隔多種網際網路媒體型式
            // text/html,.txt,.csv
            $accept = $input_setting['accept'] ?? '';
            if (!empty($accept)) {
                $input_setting['accept'] = 'accept="' . $input_setting['accept'] . '"';
            } else {
                $input_setting['accept'] = '';
            }
            $file_path = 'storage/' . config('frontend.upload_path') . '/' . $input_setting['upload_path'] . $input_setting['input_value'];
            if (empty($input_setting['image_string'])) {
                if (isset($input_setting['file_name_display_value'])) {
                    $input_setting['image_string'] = $input_setting['file_name_display_value'];
                } else {
                    $input_setting['image_string'] = $input_setting['input_value'];
                }
                $input_setting['prepend_str'] = '<a href="' . asset($file_path) . '" target="_blank">';
                $input_setting['depend_str'] = '</a>';
            }
        }
        if ($input_type == 'value') {
            $input_setting['input_grid_class'] .= ' pt-2';
            if (!empty($input_setting['image_string'])) {
                $input_setting['input_value'] = $input_setting['image_string'];
            }
        }
        // if ($input_type == 'custom') {
        //     $input_setting['input_value'] = $input_setting['image_string'];
        // }
        if ($input_type == 'include') {
            $input_setting['include_path'] = $input_setting['include_path'];
        }
        return $input_setting;
    }

    /**
     * 選單項目迴圈
     */
    public function setOption($option, $use_array_value, $input_value)
    {
        $result_str = '';
        foreach ($option as $key => $element) {
            if (is_array($element)) {
                if (isset($element['group'])) {
                    $result_str .= '<optgroup label="' . $element['group'] . '">';
                }
                foreach ($element['option'] as $option_key => $option_element) {

                    // 選單值使用 array key 或 array value
                    $option_value = $use_array_value ? $option_element : $option_key;
                    if (is_array($input_value)) {

                        // 複選時選取項目的判定
                        $selected = in_array($option_value, $input_value) ? ' selected="selected"' : '';
                    } else {
                        $selected = $input_value == $option_value ? ' selected="selected"' : '';
                    }
                    $result_str .= '<option value="' . $option_value . '"' . $selected . '>' . $option_element . '</option>';
                }
                if (isset($element['group'])) {
                    $result_str .= '</optgroup>';
                }
            } else {

                // 選單值使用 array key 或 array value
                $option_value = $use_array_value ? $element : $key;

                // 複選時選取項目的判定
                if (is_array($input_value)) {

                    // 複選時選取項目的判定
                    $selected = in_array($option_value, $input_value) ? ' selected="selected"' : '';
                } else {
                    $selected = $input_value == $option_value ? ' selected="selected"' : '';
                }
                $result_str .= '<option value="' . $option_value . '"' . $selected . '>' . $element . '</option>';
            }
        }
        return $result_str;
    }
}
