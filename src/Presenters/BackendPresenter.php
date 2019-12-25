<?php

namespace Onepoint\Dashboard\Presenters;

use Illuminate\Support\Str;
use Onepoint\Dashboard\Services\RouteService;

/**
 * 後台樣版輔助方法
 */
class BackendPresenter
{
    private $is_root = false;
    public $auth_guard = false;

    /**
     * 產生主導覽
     */
    public function setNavi($element)
    {
        if (config('auth_guard', false)) {
            $this->auth_guard = config('auth_guard');
        }
        $active = '';
        $url = '';
        $icon = $element['icon'];
        $title = $element['title'];
        $sub_item = [];
        $parent_show_string = '';
        $parent_permission = false;
        if (isset($element['action'])) {

            // 沒有子項目的連結
            $url = action($element['action']);
            if (url()->current() == $url) {
                $active = 'active';
            }

            // 同一個 controller 下的方法都設定 active
            $controller_name = Str::after(Str::kebab(Str::before(class_basename($element['action']), 'Controller@')), '\\-');
            $act_res = RouteService::is(config('dashboard.uri') . '/' . $controller_name . '/*');
            if ($act_res) {
                $active = 'active';
            }

            // 其他要設定 active 的功能
            if (isset($element['includes'])) {
                foreach ($element['includes'] as $include_url) {
                    $include_url_arr[] = action($include_url);
                }
                if (in_array(url()->current(), $include_url_arr)) {
                    $active = 'active';
                }
            }

            // Query string
            if (isset($element['query_string'])) {
                $url .= '?' . http_build_query($element['query_string']);
            }

            // 權限
            $role_controller_str = Str::after(Str::before($element['action'], '@'), '\\');
            if ($this->auth_guard) {
                if (auth()->guard($this->auth_guard)->user()->hasAccess(['read-' . $role_controller_str]) || $this->is_root) {
                    $parent_permission = true;
                }
            } else {
                if (auth()->user()->hasAccess(['read-' . $role_controller_str]) || $this->is_root) {
                    $parent_permission = true;
                }
            }
        } else {
            
            // 子項目
            $parent_show = false;
            $parent_permission = false;
            foreach ($element['sub'] as $key => $value) {
                $include_url_arr = [];
                $sub_active = '';
                if (isset($value['action'])) {
                    $role_controller_str = Str::after(Str::before($value['action'], '@'), '\\');
                    $url = action($value['action']);
                    if (url()->current() == $url) {
                        $sub_active = 'active';
                    }

                    // 同一個 controller 下的方法都設定 active
                    // $controller_name = Str::after(Str::kebab(Str::before($value['action'], 'Controller@')), '\\-');
                    $controller_name = Str::after(Str::kebab(Str::before(class_basename($value['action']), 'Controller@')), '\\-');
                    $act_res = RouteService::is(config('dashboard.uri') . '/' . $controller_name . '/*');
                    if ($act_res) {
                        $parent_show = true;
                        $sub_active = 'active';
                    }
                }

                // 其他要active的功能
                if (isset($value['includes'])) {
                    foreach ($value['includes'] as $include_url) {
                        $include_url_arr[] = action($include_url);
                    }
                    if (in_array(url()->current(), $include_url_arr)) {
                        $sub_active = 'active';
                        $parent_show = true;
                    }
                }

                // 判斷是否有權限
                if ($this->auth_guard) {
                    if (auth()->guard($this->auth_guard)->user()->hasAccess(['read-' . $role_controller_str]) || $this->is_root) {
                        $permission = true;
                        $parent_permission = true;
                    } else {
                        $permission = false;
                    }
                } else {
                    if (auth()->user()->hasAccess(['read-' . $role_controller_str]) || $this->is_root) {
                        $permission = true;
                        $parent_permission = true;
                    } else {
                        $permission = false;
                    }
                }
                $sub_item[] = [
                    'active' => $sub_active,
                    'url' => $url,
                    'title' => $value['title'],
                    'permission' => $permission,
                ];
            }

            // 判斷是否展開第二層選單
            if ($parent_show) {
                $parent_show_string = 'show';
            }
        }
        return compact('active', 'url', 'icon', 'title', 'sub_item', 'parent_show_string', 'parent_permission');
    }
}