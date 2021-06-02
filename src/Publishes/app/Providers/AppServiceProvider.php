<?php

namespace App\Providers;

use App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $app_url = 'https://default.test';
        if (isset($_SERVER['HTTP_HOST'])) {
            if (request()->isSecure()) {
                $ssl_protocol = 'https://';
            } else {
                $ssl_protocol = 'http://';
            }
            $app_url = $ssl_protocol . $_SERVER['HTTP_HOST'];
        }
        define("APP_URL", $app_url);
        // $this->app->bind('path.public', function() {
        //     return $_SERVER['DOCUMENT_ROOT'];
        // });

        $http_host = 'default';

        // 資料庫設定
        $mysql_host = 'localhost';
        $mysql_database = 'default';
        $mysql_username = 'homestead';
        $mysql_password = 'secret';
        if (isset($_SERVER['HTTP_HOST'])) {
            switch ($_SERVER['HTTP_HOST']) {
                case 'default.test':
                    $http_host = 'default';
                    $mysql_host = 'localhost';
                    $mysql_database = 'default';
                    $mysql_username = 'homestead';
                    $mysql_password = 'secret';
                    break;

                default:
                    abort(404);
            }
        }
        
        // 查詢分站網址後綴
        // use Onepoint\Reading\Entities\Site;
        // $query = Site::where('suffix_url', request()->segment(1))->first();
        // if (!is_null($query)) {
        //     config(['suffix_url' => $query->suffix_url . '/']);
        // }
        $suffix_url = '';

        // 加入網址後綴
        $uri = 'backend';
        if (!empty($suffix_url)) {
            $uri = $suffix_url . $uri;
        }
        $login_default_uri = $uri . '/' . 'dashboard/index';

        // 各項初始設定值
        config([
            'http_host' => $http_host,
            'suffix_url' => $suffix_url,
        
            // 資料庫
            // 'database.connections.mysql.host' => $mysql_host,
            'database.connections.mysql.database' => $mysql_database,
            'database.connections.mysql.username' => $mysql_username,
            'database.connections.mysql.password' => $mysql_password,
        
            'app.debug' => true,
            'app.name' => '後台管理系統',
            'http_host' => $http_host,
            'today' => date('Y-m-d'),
        
            'dashboard' => [
                'layout_file' => 'assets/dashboard/',
                'view_path' => 'dashboard',
                'uri' => $uri,
        
                // 登入後預設首頁
                'login_default_uri' => $login_default_uri,
            ],
        
            // 啟用欄位名稱值設定
            'db_status_name' => 'status',
            'db_status_true_string' => '啟用',
            'db_status_false_string' => '停用',
        
            // 前台
            'frontend' => [
        
                // 前台網址
                'url' => APP_URL,
        
                // 上傳資料夾
                'upload_path' => 'uploads/' . $http_host,
        
                // 資源路徑
                // 'assets_frontend_path' => 'assets/',
        
                // 無圖片時顯示的字串
                // 'upload_image_default_string' => '',
            ],
        ]);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
        // \URL::forceScheme('https');

        $db_name = 'default';
        $request_db_name = request('db_name', false);
        if ($request_db_name) {
            Cache::forever('db_name', $request_db_name);
            $db_name = $request_db_name;
        } else {
            if (Cache::has('db_name')) {
                $db_name = Cache::get('db_name');
            }
        }

        if ($db_name != 'default') {
            switch ($db_name) {
                case 'local':
                    $mysql_host = 'localhost';
                    $mysql_database = 'admin_db';
                    $mysql_username = 'admin_db';
                    $mysql_password = '';
                    break;
                
                // 測試機
                case 'dev':
                    $mysql_host = 'localhost';
                    $mysql_database = 'admin_db';
                    $mysql_username = 'admin_db';
                    $mysql_password = '';
                    break;
            }
            config([
                // 'http_host' => $http_host,
                // 'suffix_url' => $suffix_url,

                // 資料庫
                'database.connections.mysql.host' => $mysql_host,
                'database.connections.mysql.database' => $mysql_database,
                'database.connections.mysql.username' => $mysql_username,
                'database.connections.mysql.password' => $mysql_password,
            ]);
        }
        
        // 後台設定值
        if (request()->segment(0) == config('backend.dashboard.uri')) {
            config([
                'backend' => [
                    'footer_copyright' => '<a href="#!">Onepoint</a>',
            
                    // 網頁標題
                    'html_page_title' => __('dashboard::backend.網站內容管理系統'),
            
                    // 網頁關鍵字
                    'meta_keywords' => __('dashboard::backend.網站內容管理系統'),
            
                    // 網頁敘述
                    'meta_description' => __('dashboard::backend.網站內容管理系統'),
            
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
                        'header_text' => __('dashboard::backend.網站內容管理系統')
                    ],
                    'navigation_item' => [
                        ['title' => '訂單', 'translation' => 'base::order-form.', 'icon' => 'far fa-credit-card', 'action' => '\Onepoint\Base\Controllers\OrderController@index'],
                        ['title' => '會員', 'translation' => 'base::user.', 'icon' => 'fas fa-user', 'action' => '\Onepoint\Base\Controllers\UserController@index'],
                        ['title' => '活動', 'translation' => 'base::activity.', 'icon' => 'fas fa-calendar-alt', 'action' => '\Onepoint\Base\Controllers\ActivityController@index'],
                        ['title' => '折扣碼', 'translation' => 'base::coupon.', 'icon' => 'fas fa-percentage', 'action' => '\Onepoint\Base\Controllers\CouponCategoryController@index', 'includes' => [
                            '\Onepoint\Base\Controllers\CouponController@index',
                            '\Onepoint\Base\Controllers\CouponController@update',
                            '\Onepoint\Base\Controllers\CouponController@detail',
                        ]],
                        ['title' => '聯絡表單', 'translation' => 'base::contact.', 'icon' => 'far fa-comment-dots', 'action' => '\Onepoint\Base\Controllers\ContactController@index'],
                        ['title' => '文章', 'translation' => 'base::article.', 'icon' => 'far fa-file-alt', 'action' => '\Onepoint\Base\Controllers\ArticleController@index', 'includes' => [
                            '\Onepoint\Base\Controllers\ArticleCategoryController@index',
                            '\Onepoint\Base\Controllers\ArticleCategoryController@update',
                            '\Onepoint\Base\Controllers\ArticleCategoryController@detail',
                        ]],
                        ['title' => '輪播圖', 'translation' => 'base::slider.', 'icon' => 'fas fa-photo-video', 'action' => '\Onepoint\Base\Controllers\SliderController@index'],
                        ['title' => '部落格', 'translation' => 'base::blog.', 'icon' => 'fas fa-blog', 'action' => '\Onepoint\Base\Controllers\BlogController@index', 'includes' => [
                            '\Onepoint\Base\Controllers\BlogCategoryController@index',
                            '\Onepoint\Base\Controllers\BlogCategoryController@update',
                            '\Onepoint\Base\Controllers\BlogCategoryController@detail',
                        ]],
                        ['title' => '行政地區', 'translation' => 'base::order-form.', 'icon' => 'fas fa-map-marker-alt', 'action' => '\Onepoint\Base\Controllers\DeliveryCountyController@index', 'includes' => [
                            '\Onepoint\Base\Controllers\DeliveryDistrictController@index',
                            '\Onepoint\Base\Controllers\DeliveryDistrictController@update',
                            '\Onepoint\Base\Controllers\DeliveryDistrictController@detail',
                        ]],
                        ['title' => '權限管理', 'translation' => 'dashboard::auth.', 'icon' => 'fas fa-user-lock', 'sub' => [
                            ['title' => '人員管理', 'translation' => 'dashboard::auth.', 'action' => '\Onepoint\Base\Controllers\UserController@index'],
                            ['title' => '人員群組', 'translation' => 'dashboard::auth.', 'action' => '\Onepoint\Dashboard\Controllers\RoleController@index'],
                        ]],
                        ['title' => '網站設定', 'translation' => 'dashboard::setting.', 'icon' => 'fas fa-cogs', 'action' => '\Onepoint\Dashboard\Controllers\SettingController@model', 'method' => 'model', 'includes' => [
                            '\Onepoint\Dashboard\Controllers\SettingController@index',
                            '\Onepoint\Dashboard\Controllers\SettingController@update',
                            '\Onepoint\Dashboard\Controllers\SettingController@detail',
                        ]],
                    ],
            
                    // 權限項目
                    'permissions' => [
                        '訂單' => ['controller' => 'Onepoint\Base\Controllers\OrderFormController', 'permission' => ['read' => '檢視', 'update' => '修改', 'create' => '新增', 'delete' => '刪除']],
                        '會員' => ['controller' => 'Onepoint\Base\Controllers\MemberController', 'permission' => ['read' => '檢視', 'update' => '修改', 'create' => '新增', 'delete' => '刪除']],
                        '活動' => ['controller' => 'Onepoint\Base\Controllers\ActivityController', 'permission' => ['read' => '檢視', 'update' => '修改', 'create' => '新增', 'delete' => '刪除']],
                        '折扣碼' => ['controller' => 'Onepoint\Base\Controllers\CouponCategoryController', 'permission' => ['read' => '檢視', 'update' => '修改', 'create' => 'update', 'delete' => 'update']],
                        '聯絡表單' => ['controller' => 'Onepoint\Base\Controllers\ContactController', 'permission' => ['read' => '檢視', 'update' => '修改', 'create' => 'update', 'delete' => 'update']],
                        '文章' => ['controller' => 'Onepoint\Base\Controllers\ArticleController', 'permission' => ['read' => '檢視', 'update' => '修改', 'create' => '新增', 'delete' => '刪除']],
                        '輪播圖' => ['controller' => 'Onepoint\Base\Controllers\SliderController', 'permission' => ['read' => '檢視', 'update' => '修改', 'create' => '新增', 'delete' => '刪除']],
                        '部落格' => ['controller' => 'Onepoint\Base\Controllers\BlogController', 'permission' => ['read' => '檢視', 'update' => '修改', 'create' => '新增', 'delete' => '刪除']],
                        '行政地區' => ['controller' => 'Onepoint\Base\Controllers\DeliveryCountyController', 'permission' => ['read' => '檢視', 'update' => '修改', 'create' => 'update', 'delete' => 'update']],
                        '人員管理' => ['controller' => 'UserController', 'permission' => ['read' => '檢視', 'update' => '修改', 'create' => '新增', 'delete' => '刪除']],
                        '人員群組' => ['controller' => 'RoleController', 'permission' => ['read' => '檢視', 'update' => '修改', 'create' => '新增', 'delete' => '刪除']],
                        '網站設定' => ['controller' => 'SettingController', 'permission' => ['read' => '檢視', 'update' => '修改']],
                    ],
            
                    // 語言版本
                    'language' => [
                        'zh-tw' => __('dashboard::backend.繁體中文'),
                        'en' => __('dashboard::backend.英文'),
                    ],
            
                    // 狀態
                    'status_item' => ['啟用' => __('dashboard::backend.啟用'), '停用' => __('dashboard::backend.停用')],
            
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
        }
        
        // 設定語言
        if (request('lang', false)) {
            if (isset(config('backend.language')[config('app.locale')])) {
                Cache::forever('backend_language', request('lang'));
                App::setLocale(Cache::get('backend_language', 'zh-tw'));
            }
        }

        // 避免語言參數錯誤
        if (!isset(config('backend.language')[config('app.locale')])) {
            Cache::forever('backend_language', 'zh-tw');
            App::setLocale('zh-tw');
        }
    }
}
