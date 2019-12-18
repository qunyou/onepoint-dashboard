<?php
config([
    'backend' => [
        'footer_copyright' => '<a href="#!">Hiyou</a>',

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

            // 可用值 blue,azure,green,orange,red,purple
            'color' => 'black',

            // 背景圖
            'img' => 'assets/dashboard/img/sidebar-1.jpg',
            
            // 標題
            'header_text' => __('backend.網站內容管理系統')
        ],
        'navigation_item' => [
            ['title' => __('article.圖書館簡介'), 'icon' => 'far fa-file-alt', 'sub' => [
                ['title' => __('article.文章'), 'action' => 'ArticleController@index'],
                ['title' => __('article.文章分類'), 'action' => 'ArticleCategoryController@index'],
            ]],
            // ['title' => __('news.新聞管理'), 'icon' => 'far fa-file-alt', 'sub' => [
            //     ['title' => __('news.新聞'), 'action' => 'NewsController@index'],
            //     ['title' => __('news.新聞分類'), 'action' => 'NewsCategoryController@index'],
            // ]],
            ['title' => __('resource.資源管理'), 'icon' => 'fas fa-link', 'sub' => [
                ['title' => __('resource.資源'), 'action' => 'ResourceController@index'],
                ['title' => __('resource.資源分類'), 'action' => 'ResourceCategoryController@index'],
            ]],
            ['title' => __('download.下載管理'), 'icon' => 'fas fa-cloud-download-alt', 'sub' => [
                ['title' => __('download.下載'), 'action' => 'DownloadController@index'],
                ['title' => __('download.下載分類'), 'action' => 'DownloadCategoryController@index'],
            ]],
            // ['title' => __('album.相簿管理'), 'icon' => 'far fa-file-alt', 'sub' => [
            //     ['title' => __('album.相片'), 'action' => 'AlbumImageController@index'],
            //     ['title' => __('album.相簿'), 'action' => 'AlbumController@index'],
            //     ['title' => __('album.相簿分類'), 'action' => 'AlbumCategoryController@index'],
            // ]],
            ['title' => __('activity.圖書館花絮'), 'icon' => 'far fa-images', 'sub' => [
                ['title' => __('album.相片'), 'action' => 'AlbumImageController@index'],
                ['title' => __('album.相簿'), 'action' => 'AlbumController@index'],
                ['title' => __('album.相簿分類'), 'action' => 'AlbumCategoryController@index'],
            ]],
            ['title' => __('page.優良作品'), 'icon' => 'far fa-file-alt', 'action' => 'PageController@index'],
            ['title' => __('reading/book.書目管理'), 'icon' => 'fas fa-book', 'sub' => [
                // ['title' => __('book.試卷測驗結果'), 'action' => '\Onepoint\Reading\Controllers\ExaminationResultController@index'],
                ['title' => __('reading/book.試題'), 'action' => '\Onepoint\Reading\Controllers\BookQuestionController@index'],
                ['title' => __('reading/book.書目'), 'action' => '\Onepoint\Reading\Controllers\BookController@index'],
                ['title' => __('reading/book.書目分類'), 'action' => '\Onepoint\Reading\Controllers\BookCategoryController@index'],
                ['title' => __('reading/book.試卷領域'), 'action' => '\Onepoint\Reading\Controllers\ExaminationFieldController@index'],
            ]],
            ['title' => __('reading/reader.讀者'), 'icon' => 'fas fa-book-reader', 'sub' => [
                ['title' => __('reading/reader.讀者'), 'action' => '\Onepoint\Reading\Controllers\ReaderController@index'],
                ['title' => __('reading/reader.讀者類型'), 'action' => '\Onepoint\Reading\Controllers\ReaderCategoryController@index'],
            ]],
            ['title' => __('reading/department.單位'), 'icon' => 'far fa-building', 'action' => '\Onepoint\Reading\Controllers\DepartmentController@index'],
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
            '文章' => ['controller' => 'ArticleController', 'permission' => ['read' => '檢視', 'update' => '修改', 'create' => '新增', 'delete' => '刪除']],
            '文章分類' => ['controller' => 'ArticleCategoryController', 'permission' => ['read' => '檢視', 'update' => '修改', 'create' => '新增', 'delete' => '刪除']],
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
                'article' => '文章',
                'news' => '新聞',
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
            'csv_url' => 'https://docs.google.com/spreadsheets/d/1KRp1qwRSE7f-Q1tvHeeO39XYq0Xvh7QXY24GqO-dsIU/edit?usp=sharing',

            // 前台預覽網址
            'preview_url' => 'article'
        ],

        /**
         * 書目
         */
        'book' => [

            // 是否公佈解答
            'public_answer_item' => ['不公佈' => __('reading/book.不公佈'), '公佈' => __('reading/book.公佈')],

            // 正確答案
            'correct_answer_item' => [
                '選項1' => __('reading/book.選項1'),
                '選項2' => __('reading/book.選項2'),
                '選項3' => __('reading/book.選項3'),
                '選項4' => __('reading/book.選項4'),
                '選項5' => __('reading/book.選項5'),
            ],
        ],
    ]
]);
