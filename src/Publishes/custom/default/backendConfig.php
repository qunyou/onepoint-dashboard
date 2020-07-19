<?php
config([
    'backend' => [
        'footer_copyright' => '<a href="#!">Onepoint</a>',

        // 網頁標題
        'html_page_title' => __('dashbaord::backend.網站內容管理系統'),

        // 網頁關鍵字
        'meta_keywords' => __('dashbaord::backend.網站內容管理系統'),

        // 網頁敘述
        'meta_description' => __('dashbaord::backend.網站內容管理系統'),

        // 網頁圖示
        'favicon' => 'assets/dashboard/img/favicon.ico',

        // 預設分頁筆數
        'paginate' => 20,

        /**
         * 主導覽
         */
        'sidebar' => [

            // 可用值 black,blue,azure,green,orange,red,purple
            'color' => 'purple',

            // 背景圖
            'img' => 'assets/dashboard/img/sidebar-1.jpg',
            
            // 標題
            'header_text' => __('dashbaord::backend.網站內容管理系統')
        ],
        'navigation_item' => [
            ['title' => '權限管理', 'translation' => 'dashboard::auth.', 'icon' => 'fas fa-user-lock', 'sub' => [
                ['title' => '人員管理', 'translation' => 'dashboard::auth.', 'action' => '\Onepoint\Dashboard\Controllers\UserController@index'],
                ['title' => '人員群組', 'translation' => 'dashboard::auth.', 'action' => '\Onepoint\Dashboard\Controllers\RoleController@index'],
            ]],
            ['title' => '網站設定', 'translation' => 'dashboard::setting.', 'icon' => 'fas fa-cogs', 'action' => '\Onepoint\Dashboard\Controllers\SettingController@model', 'method' => 'model', 'includes' => [
                '\Onepoint\Dashboard\Controllers\SettingController@index',
                '\Onepoint\Dashboard\Controllers\SettingController@update',
                '\Onepoint\Dashboard\Controllers\SettingController@detail',
            ]]
        ],

        // 權限項目
        'permissions' => [
            '人員管理' => ['controller' => 'UserController', 'permission' => ['read' => '檢視', 'update' => '修改', 'create' => '新增', 'delete' => '刪除']],
            '人員群組' => ['controller' => 'RoleController', 'permission' => ['read' => '檢視', 'update' => '修改', 'create' => '新增', 'delete' => '刪除']],
            '網站設定' => ['controller' => 'SettingController', 'permission' => ['read' => '檢視', 'update' => '修改']],
        ],

        // 語言版本
        'language' => [
            'zh-tw' => __('dashbaord::backend.繁體中文'),
            'en' => __('dashbaord::backend.英文'),
        ],

        // 狀態
        'status_item' => ['啟用' => __('dashbaord::backend.啟用'), '停用' => __('dashbaord::backend.停用')],

        // 性別
        'gender_item' => ['男' => __('auth.男'), '女' => __('auth.女')],

        // 連結開啟方式
        'url_target_item' => ['預設分頁' => '預設分頁', '另開分頁' => '另開分頁'],

        // 縮圖資料夾及尺寸
        'image_scale_setting' => [
            ['path' => 'large', 'width' => 1170],
            ['path' => 'normal', 'width' => 700],
            ['path' => 'thumb', 'width' => 200]
        ],

        // 設定功能
        'setting' => [
            'type' => [
                'number' => 'number',
                'text' => 'text',
                'textarea' => 'textarea',
                'editor' => 'editor',
                'file_name' => 'file_name',
                'color' => 'color',
            ],
            'model' => [
                'global' => '全域',
            ]
        ],

        /**
         * tinymce
         */
        'tinymce' => [

            // 傳檔上限
            'file_limit' => 1000
        ],
    ]
]);
