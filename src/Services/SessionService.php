<?php

namespace Onepoint\Dashboard\Services;

use Session;

class SessionService
{
    /**
     * 檢查表單資料，並以 session 記錄錯誤訊息
     * @param  array  $setting_array [description]
     * @return [type]                [description]
     */
    public function inputCheck($setting_array = [])
    {
        if (count($setting_array)) {
            Session::forget('message');
            foreach ($setting_array as $key => $value) {
                $request_value = request($key, false);
                if ($request_value !== false) {
                    switch ($value['condition']) {
                        case '>0':
                            if ($request_value <= 0) {
                                session(['message.' . $key => $value['error_message']]);
                            }
                            break;
                    }
                }
            }
            if (Session::get('message', false)) {
                return false;
            } else {
                return true;
            }
        }
    }
}
