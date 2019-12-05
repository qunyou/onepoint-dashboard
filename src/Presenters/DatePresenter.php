<?php

namespace App\Presenters;

/**
 * 日期輔助方法
 */
class DatePresenter
{
    /**
     * 產生 vue-date 日期格式
     */
    static function vueDateFormat($form_name)
    {
        $result_string = "''";
        $request_string = request($form_name, '');
        if (!empty($request_string)) {
            $yyyy = date('Y', strtotime($request_string));
            $mm = date('m', strtotime($request_string));
            $dd = date('d', strtotime($request_string));
            $result_string = 'new Date("' . $yyyy . '/' . $mm . '/' . $dd . '")';
        }
        return $result_string;
    }
}