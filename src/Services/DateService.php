<?php

namespace App\Services;

use Illuminate\Support\Str;

class DateService
{
    /**
     * 產生日期區間選單
     */
    public function getDateSelect($form_title, $form_name, $use_defalt = false)
    {
        $tpl_data['form_title'] = $form_title;
        $tpl_data['form_name'] = $form_name;
        $tpl_data['use_defalt'] = $use_defalt;
        return view(config('site.view_backend_path') . '.partials.date_select', $tpl_data);
    }

    /**
     * 產生日期選單
     */
    public function getDateDropdownSelect($form_title, $form_name, $default = '', $input_only = false, $inline_style = false)
    {
        $tpl_data['form_title'] = $form_title;
        $tpl_data['form_name'] = $form_name;
        if (empty($default) || $default == '0000-00-00') {
            $tpl_data['yyyy'] = '';
            $tpl_data['mm'] = '';
            $tpl_data['dd'] = '';
        } else {
            $default = explode('-', $default);
            $tpl_data['yyyy'] = $default[0];
            $tpl_data['mm'] = $default[1];
            $tpl_data['dd'] = $default[2];
        }

        // 判斷是否使用 inline 樣式
        if ($inline_style) {
            $tpl_data['inline_style'] = ' inline-item';
        } else {
            $tpl_data['inline_style'] = '';
        }
        $tpl_data['input_only'] = $input_only;
        return view(config('site.view_backend_path') . '.partials.date_dropdown_select', $tpl_data);
    }

    /**
     * 產生時間選單
     */
    public function getTimeDropdownSelect($form_title, $form_name, $default = '')
    {
        $tpl_data['form_title'] = $form_title;
        $tpl_data['form_name'] = $form_name;
        if (empty($default) || $default == '00:00:00') {
            $default = date('H:i:s');
            $tpl_data['HH'] = '';
            $tpl_data['ii'] = '';
        } else {
            $default = explode(':', $default);
            $tpl_data['HH'] = $default[0];
            $tpl_data['ii'] = $default[1];
        }
        return view(config('site.view_backend_path') . '.partials.time_dropdown_select', $tpl_data);
    }

    /**
     * 產生日期區間選單
     */
    public function getDateIntervalSelect($form_title, $use_defalt = false, $start_form_name, $end_form_name, $start_date = '', $end_date = '')
    {
        $tpl_data['form_title'] = $form_title;
        $tpl_data['start_form_name'] = $start_form_name;
        $tpl_data['end_form_name'] = $end_form_name;
        $tpl_data['use_defalt'] = $use_defalt;
        if(empty($start_date) || $start_date == '0000-00-00') {
            $tpl_data['start_yyyy'] = '';
            $tpl_data['start_mm'] = '';
            $tpl_data['start_dd'] = '';
        } else {
            $start_date = explode('-', $start_date);
            $tpl_data['start_yyyy'] = $start_date[0];
            $tpl_data['start_mm'] = $start_date[1];
            $tpl_data['start_dd'] = $start_date[2];
        }

        if(empty($end_date) || $end_date == '0000-00-00') {
            $tpl_data['end_yyyy'] = '';
            $tpl_data['end_mm'] = '';
            $tpl_data['end_dd'] = '';
        } else {
            $end_date = explode('-', $end_date);
            $tpl_data['end_yyyy'] = $end_date[0];
            $tpl_data['end_mm'] = $end_date[1];
            $tpl_data['end_dd'] = $end_date[2];
        }

        // 判斷是否使用 inline 樣式
        // if ($inline_style) {
        //     $tpl_data['inline_style'] = ' inline-item';
        // } else {
        //     $tpl_data['inline_style'] = '';
        // }
        // $tpl_data['input_only'] = $input_only;
        return view(config('site.view_backend_path') . '.partials.date_interval_select', $tpl_data);
    }

    /**
     * 字串製作成日期格式(匯入資料用)
     *
     * @return String
     */
    public function getDateFormat($str, $default_str = '0000-00-00')
    {
        $slash_arr = explode('/', $str);
        $dash_arr = explode('-', $str);
        if (count($slash_arr) > 1) {
            $check_date_result_array = $this->checkDateFormat($slash_arr);
        } elseif(count($dash_arr) > 1) {
            $check_date_result_array = $this->checkDateFormat($dash_arr);
        } else {

            // 日期為一般字串
            $yyyy = '';
            $mm = '';
            $dd = '';
            switch (strlen($str)) {
                case 4:
                    $str .= '0101';
                    break;
                case 6:
                    $str .= '01';
                    break;
                case 8:
                    break;
                default:
                    $str = '';
                    break;
            }
            if (!empty($str)) {
                $yyyy = substr($str, 0, 4);
                $mm = substr($str, 4, 2);
                $dd = substr($str, 6, 2);
            }
            $check_date_result_array = ['yyyy' => $yyyy, 'mm' => $mm, 'dd' => $dd];
        }
        $chk = checkdate((int)$check_date_result_array['mm'], (int)$check_date_result_array['dd'], (int)$check_date_result_array['yyyy']);
        if ($chk) {
            return date('Y-m-d', strtotime(
                (int)$check_date_result_array['yyyy'] . '-' .
                (int)$check_date_result_array['mm'] . '-' .
                (int)$check_date_result_array['dd']
                )
            );
        }
        return $default_str;
    }

    /**
     * 檢查日期格式(匯入資料用)
     *
     * @return String
     */
    public static function checkDateFormat($arr)
    {
        $yyyy = '0000';
        $mm = '00';
        $dd = '00';

        // 判斷格式是 mm/dd/yyyy 還是 yyyy/mm/dd
        if ($arr[0] > 12) {

            // 日期為 yyyy/mm/dd 格式
            // 若月日資料有問題修改成01
            if (!isset($arr[1]) || (isset($arr[1]) && $arr[1] == '00')) {
                $arr[1] = '01';
            }
            if (!isset($arr[2]) || (isset($arr[2]) && $arr[2] == '00')) {
                $arr[2] = '01';
            } else {

                // 移除時間字串
                $dd_arr = explode(' ', $arr[2]);
                if (count($dd_arr) > 1) {
                    $arr[2] = $dd_arr[0];
                }
            }
            $yyyy = isset($arr[0]) ? $arr[0] : $yyyy;
            $mm = isset($arr[1]) ? $arr[1] : $mm;
            $dd = isset($arr[2]) ? $arr[2] : $dd;
        } else {

            // 日期為 mm/dd/yyyy 格式
            // 若月日資料有問題修改成01
            if (!isset($arr[0]) || (isset($arr[0]) && $arr[0] == '00')) {
                $arr[0] = '01';
            }
            if (!isset($arr[1]) || (isset($arr[0]) && $arr[0] == '00')) {
                $arr[1] = '01';
            } else {

                // 移除時間字串
                if (isset($arr[2])) {
                    $dd_arr = explode(' ', $arr[2]);
                    if (count($dd_arr) > 1) {
                        $arr[2] = $dd_arr[0];
                    }
                }
            }
            $yyyy = isset($arr[2]) ? $arr[2] : $yyyy;
            $mm = isset($arr[0]) ? $arr[0] : $mm;
            $dd = isset($arr[1]) ? $arr[1] : $dd;
        }
        return ['yyyy' => $yyyy, 'mm' => $mm, 'dd' => $dd];
    }

    /**
     * 一般日期格式檢查
     *
     * $element     string or array 按年月日順序的字串或陣列
     *
     */
    static function isDate($element)
    {
        if (Str::contains($element, '/')) {
            $separator_string = '/';
        } elseif (Str::contains($element, '-')) {
            $separator_string = '-';
        }
        if (!is_array($element) && isset($separator_string)) {
            $element = explode($separator_string, $element);
        }
        if(is_array($element)) {
            $__y = isset($element[0]) ? $element[0] : '';
            $__m = isset($element[1]) ? $element[1] : '';
            $__d = isset($element[2]) ? $element[2] : '';
            if (is_numeric($__y) && is_numeric($__m) && is_numeric($__d) && checkdate($__m, $__d, $__y)) {
                return $__y . $separator_string . $__m . $separator_string . $__d;
            }
        }
        return false;
    }
}
