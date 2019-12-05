@inject('route_service', 'App\Services\RouteService')
<?php
$current_class_name = $tool->getCurrentAction()['class_name'];
?>
<ul class="nav nav-main">
    @foreach ($config_arr as $element)

        {{-- 是否顯示於邊欄選單 --}}
        @if (!isset($element['navi_hide']) || (isset($element['navi_hide']) && $element['navi_hide'] == false))
            @if (!isset($element['sub']))
                <?php
                // dd(session('auth.user'));

                // 製作權限名稱字串
                $permission_str = $element['controller'];
                if (isset($element['method'])) {
                    $permission_str .= '_' . $element['method'];
                }

                // 檢查權限
                if (Sentinel::inRole('root')) {
                    $allow = true;
                } else {
                    $allow = false;
                    if (isset($element['role'])) {
                        if ($element['role'] == 'all') {

                                // 不設定權限，所有人都能看
                                $allow = true;
                        } elseif ($element['role'] == 'teacher' && (session('auth.user.user_title_1') == 1 || session('auth.user.user_title_2') == 1 || session('auth.user.user_title_3') == 1 || session('auth.user.user_title_4') == 1)) {

                                // 老師可檢視
                                $allow = true;
                        } else {
                            $role_arr = explode(',', $element['role']);
                            foreach ($role_arr as $role_value) {
                                if (Sentinel::inRole($role_value)) {
                                    $allow = true;
                                    break;
                                }
                            }
                        }
                    } else {
                        if (isset(session('auth.user.permissions')[$permission_str . '.' . 'r'])) {
                            $allow = true;
                        }
                    }
                }
                ?>
                @if ($allow)
                    <?php

                    // 判斷連結網址
                    if ($element['controller'] == '#') {
                        $url = '#';
                    } else {
                        $method = isset($element['method']) ? $element['method'] : 'index';
                        $url = url(config('site.backend_uri') . '/' . $element['controller'] . '/' . $method);
                    }
                    $active = '';
                    if (str_slug($current_class_name) == str_slug($element['controller'])) {
                        $active = ' class=nav-expanded nav-active';
                    }
                    ?>
                    <li{{ $active }}>
                        <a href="{{ $url }}">
                            <i class="{{ $element['icon'] }}" aria-hidden="true"></i>
                            <span>{{ $element['title'] }}</span>
                        </a>
                    </li>
                @endif
            @else
                <?php
                // 子項目
                $sub_item = [];
                $controllers = [];

                // 增加額外 Controller Active 判斷
                if (isset($element['active_append']) && count($element['active_append'])) {
                    foreach ($element['active_append'] as $active_append_value) {
                        $controllers[] = 'App\\Http\\Controllers\\' . studly_case($active_append_value) . 'Controller';
                    }
                }
                foreach ($element['sub'] as $key => $value) {

                    // 製作權限名稱字串
                    $permission_str = $value['controller'];
                    if (isset($value['method'])) {
                        $permission_str .= '_' . $value['method'];
                    }

                    // 設定項目隱藏，只在權限設定中顯示
                    if(!isset($value['navi_hide']) || (isset($value['navi_hide']) && !$value['navi_hide'])) {
                        $controllers[] = 'App\\Http\\Controllers\\' . studly_case($value['controller']) . 'Controller';

                        // 檢查權限
                        if (Sentinel::check()->roles->count() && Sentinel::inRole('root')) {
                            $allow = true;
                        } else {
                            $allow = false;
                            if (isset($value['role'])) {
                                if ($value['role'] == 'all') {

                                    // 不設定權限，所有人都能看
                                    $allow = true;
                                } elseif ($value['role'] == 'teacher' && (session('auth.user.user_title_1') == 1 || session('auth.user.user_title_2') == 1 || session('auth.user.user_title_3') == 1 || session('auth.user.user_title_4') == 1)) {

                                    // 老師可檢視
                                    $allow = true;
                                } else {

                                    // 限定群組能檢視，以逗號分隔每個群組名稱
                                    $role_arr = explode(',', $value['role']);
                                    foreach ($role_arr as $key => $role_value) {
                                        if (Sentinel::inRole($role_value)) {
                                            $allow = true;
                                            break;
                                        }
                                    }
                                }
                            } else {

                                // 以資料庫中的權限設定來檢查是否檢視
                                $permissions = session('auth.user.permissions');
                                if (isset($permissions[$permission_str . '.' . 'r'])) {
                                    $allow = true;
                                } else {

                                    // 教學主教(特別權限，未完成)
                                    // if (session('auth.user_title_1')) {
                                    //     $allow = true;
                                    // }
                                }
                            }
                        }
                        if ($allow) {
                            $active = '';
                            $method = isset($value['method']) ? $value['method'] : 'index';
                            if (isset($value['custom_active'])) {

                                // 自訂方式檢查 active
                                switch ($value['custom_active']['type']) {

                                    // 網址參數
                                    case 'query_string':
                                        $check = true;
                                        foreach ($value['custom_active']['param'] as $param_key => $param_value) {
                                            $req = Request::get($param_key);
                                            if ($req != $param_value) {
                                                $check = false;
                                                break;
                                            }
                                        }
                                        break;

                                    // 物件方法
                                    // param 為方法完整名稱陣列 ['getIndex', 'getUpdate']
                                    case 'method':
                                        $check = false;
                                        if (str_slug($current_class_name) == str_slug($value['controller'])) {
                                            if (in_array($tool->getCurrentAction()['method'], $value['custom_active']['param'])) {
                                                $check = true;
                                            }
                                        }
                                        break;
                                }
                                if ($check) {
                                    $active = 'nav-active';
                                }
                            } else {
                                if (str_slug($current_class_name) == str_slug($value['controller'])) {
                                    $active = $tool->getCurrentAction()['method'] == 'get' . studly_case($method) ? 'nav-active' : '';
                                }
                            }

                            // 增加額外 Controller Active 判斷
                            if (isset($value['active_append']) && count($value['active_append'])) {
                                foreach ($value['active_append'] as $active_append_value) {
                                    $controllers[] = 'App\\Http\\Controllers\\' . studly_case($active_append_value) . 'Controller';
                                    if (str_slug($current_class_name) == str_slug($active_append_value)) {
                                        $active = 'nav-active';
                                    }
                                }
                            }

                            $qs = '';
                            if (isset($value['query_string'])) {
                                $qs = '?' . http_build_query($value['query_string']);
                            }

                            // 判斷連結網址
                            if ($value['controller'] == '#') {
                                $url = '#';
                            } else {
                                if (!empty($method)) {
                                    $method = '/' . $method;
                                }
                                $url = url(config('site.backend_uri') . '/' . $value['controller'] . $method . $qs);
                            }
                            $sub_item[] = [
                                'active' => $active,
                                'url' => $url,
                                'title' => $value['title']
                            ];
                        }
                    }
                }
                ?>
                @if (count($sub_item))
                    <li class="nav-parent{{ $route_service->action($controllers, '', [],' nav-expanded nav-active') }}">
                        <a>
                            <i class="{{ $element['icon'] }}" aria-hidden="true"></i>
                            <span>{{ $element['title'] }}</span>
                        </a>
                        <ul class="nav nav-children">
                            @foreach ($sub_item as $item)
                                <li class="{{ $item['active'] }}">
                                    <a href="{{ $item['url'] }}">{{ $item['title'] }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endif
            @endif
        @endif
    @endforeach
</ul>
