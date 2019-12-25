<?php
config([
    'backend' => [
        'footer_copyright' => '<a href="#!">Onepoint</a>',

        // 網頁標題
        'html_page_title' => __('backend.網站內容管理系統'),

        // 網頁關鍵字
        'meta_keywords' => __('backend.網站內容管理系統'),

        // 網頁敘述
        'meta_description' => __('backend.網站內容管理系統'),

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
            'header_text' => __('backend.網站內容管理系統')
        ],
        'navigation_item' => [
            ['title' => __('auth.權限管理'), 'icon' => 'fas fa-user-lock', 'sub' => [
                ['title' => __('auth.人員管理'), 'action' => 'UserController@index'],
                ['title' => __('auth.人員群組'), 'action' => 'RoleController@index'],
            ]],
            ['title' => __('setting.網站設定'), 'icon' => 'fas fa-cogs', 'action' => 'SettingController@model', 'method' => 'model', 'includes' => [
                'SettingController@index',
                'SettingController@update',
                'SettingController@detail',
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
            'zh-tw' => __('backend.繁體中文'),
            'en' => __('backend.英文'),
        ],

        // 狀態
        'status_item' => ['啟用' => __('backend.啟用'), '停用' => __('backend.停用')],

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

        // 連結開啟方法
        'link_target_item' => ['預設分頁' => '預設分頁', '另開分頁' => '另開分頁'],

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

        /**
         * 文章
         */
        'article' => [

            // 匯入檔
            'csv_url' => asset('storage/uploads/default/templates/default.xlsx'),

            // 前台預覽網址
            'preview_url' => 'article'
        ],
    ]
]);
