<?php

namespace Onepoint\Dashboard\Services;

use Route;
use Str;
// use Request;

class RouteService
{
    /**
     * 取得目前 route 名稱及方法
     *
     * @return string
     */
    public static function getCurrentAction()
    {
        if (isset($_SERVER['HTTP_HOST'])) {
            $action = Route::current()->getActionName();
        } else {
            $action = '';
        }

        // 目前網址路徑
        // return request()->path();
        $basename = '';
        $class = '';
        $class_name = '';
        $method = '';
        if (!empty($action)) {
            list($class, $method) = explode('@', $action);
            $basename = class_basename($class);
            // $class_name = snake_case(str_replace('Controller', '', $basename));

            // L6 fix
            $class_name = Str::snake(str_replace('Controller', '', $basename));
        }
        // Log::info('getCurrentAction', ['class' => $class, 'method' => $method, 'class_name' => $class_name]);
        return ['controller' => $class, 'method' => $method, 'basename' => $basename, 'class_name' => $class_name];
    }

    /**
     * 目前 Controller 判斷
     *
     * $controller          array | string  物件名稱，小寫單數駝峰式命名
     * $method              array | string  方法名稱
     * $query_string_array  array           query string
     * $action_str  strint                  返回的字串
     * @return string
     */
    public static function action($controller, $method = '', $query_string_array = [], $action_str = 'active')
    {
        $controller_check = false;
        // $current_controller = RouteService::getCurrentAction()['basename'];
        // $current_controller = RouteService::getCurrentAction()['controller'];
        if (is_array($controller)) {
            $controller_array = [];
            foreach ($controller as $value) {
                $controller_array[] = action($value);
            }
            $controller_check = in_array(url()->current(), $controller_array);
        } else {
            if (url()->current() == action($controller)) {
                $controller_check = true;
            }
        }
        if ($controller_check) {
            // query string 判斷
            if (is_array($query_string_array) && count($query_string_array)) {
                foreach ($query_string_array as $key => $value) {
                    if (request($key, '') == $value) {
                        return $action_str;
                    }
                }
            } else {
                return $action_str;
            }
        }
        return '';
    }

    /**
     * 判斷是否符合目前網址路徑
     *
     * @return string
     */
    public static function is($path, $action_str = 'active')
    {
        if (request()->is($path)) {
            return $action_str;
        }
        return '';
    }
}
