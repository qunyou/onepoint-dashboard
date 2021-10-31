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
    public static function setNavi($element)
    {
        if (config('auth_guard', false)) {
            $auth_guard = config('auth_guard');
        }
        $active = '';
        $url = '';
        $icon = $element['icon'];
        $title = $element['title'];

        // 語言設定
        if (isset($element['translation'])) {
            $title = __($element['translation'] . $element['title']);
        }
        $sub_item = [];
        $parent_show_string = '';
        $parent_permission = true;
        if (isset($element['action'])) {

            // 沒有子項目的連結
            $parent_url = action($element['action']);
            if (url()->current() == $parent_url) {
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
                $include_url_arr = [];
                foreach ($element['includes'] as $include_url) {
                    if (Str::contains($include_url, '@')) {
                        $include_url_arr[] = action($include_url);
                    } else {
                        if (request()->is($include_url)) {
                            $include_url_arr[] = url()->current();
                        }
                    }
                }
                if (in_array(url()->current(), $include_url_arr)) {
                    $active = 'active';
                }
            }

            // Query string
            if (isset($element['query_string'])) {
                $parent_url .= '?' . http_build_query($element['query_string']);
            }

            // 權限
            $permission = true;
            if (config('user.use_role')) {
                $role_controller_str = Str::after(Str::before($element['action'], '@'), '\\');
                if ($auth_guard) {
                    if (auth()->guard($auth_guard)->user()->hasAccess(['read-' . $role_controller_str]) || $this->is_root) {
                        $parent_permission = true;
                    }
                } else {
                    if (auth()->user()->hasAccess(['read-' . $role_controller_str]) || $this->is_root) {
                        $parent_permission = true;
                    }
                }
            }
        } else {
            $parent_url = false;

            // 子項目
            $parent_show = false;
            $parent_permission = false;
            foreach ($element['sub'] as $key => $value) {
                $include_url_arr = [];
                $sub_active = '';
                if (isset($value['action'])) {
                    $role_controller_str = Str::after(Str::before($value['action'], '@'), '\\');
                    $url = action($value['action']);

                    // 同一個 controller 下的方法都設定 active
                    $controller_name = Str::after(Str::kebab(Str::before(class_basename($value['action']), 'Controller@')), '\\-');
                    $act_res = RouteService::is(config('dashboard.uri') . '/' . $controller_name . '/*');
                    if ($act_res) {
                        $parent_show = true;
                        $sub_active = 'active';
                    }

                    // 網址後綴
                    $current_url = url()->current();
                    if (isset($value['suffix'])) {
                        $sub_active = '';
                        $url .= $value['suffix'];
                        $current_url = url()->full();
                    }

                    // 網址完全符合的項目設定 active
                    if ($current_url == $url) {
                        $sub_active = 'active';
                    }
                }

                // 其他要active的功能
                if (isset($value['includes'])) {
                    foreach ($value['includes'] as $include_url) {
                        if (Str::contains($include_url, '@')) {
                            $include_url_arr[] = action($include_url);
                        } else {
                            if (request()->is($include_url)) {
                                $include_url_arr[] = url()->current();
                            }
                        }
                    }
                    if (in_array(url()->current(), $include_url_arr)) {
                        $sub_active = 'active';
                        $parent_show = true;
                    }
                }

                // 判斷是否有權限
                $permission = true;
                $parent_permission = true;
                if (config('user.use_role')) {
                    if ($auth_guard) {
                        if (auth()->guard($auth_guard)->user()->hasAccess(['read-' . $role_controller_str]) || $this->is_root) {
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
                }

                // 語言設定
                if (isset($value['translation'])) {
                    $value['title'] = __($value['translation'] . $value['title']);
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
        return compact('active', 'url', 'parent_url', 'icon', 'title', 'sub_item', 'parent_show_string', 'parent_permission');
    }
}
