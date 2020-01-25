<?php
if (isset($_SERVER['HTTP_HOST'])) {
    switch ($_SERVER['HTTP_HOST']) {
        case 'default.test':
            $mysql_database = 'default';
            $mysql_username = 'homestead';
            $mysql_password = 'secret';
            break;
        default:
            $mysql_database = 'default';
            $mysql_username = 'default';
            $mysql_password = 'default';
	}
} else {

    // 在 artisan 狀態
    $mysql_database = 'default';
    $mysql_username = 'homestead';
    $mysql_password = 'secret';
}

// 設定語言
if (request('lang', false)) {
    cache()->forever('backend_language', request('lang'));
}
App::setLocale(cache('backend_language', 'zh-tw'));

config([

    // 資料庫
    'database.connections.mysql.database' => $mysql_database,
    'database.connections.mysql.username' => $mysql_username,
    'database.connections.mysql.password' => $mysql_password,

    'app.debug' => true,
    'app.name' => '後台管理系統',
    'app.url' => $app_url,
    'http_host' => $http_host,

    'dashboard' => [
        'layout_file' => 'assets/dashboard/',
        'view_path' => 'dashboard',
        'uri' => 'backend',

        // 登入後預設首頁
        'login_default_uri' => 'dashboard/index',
    ],

    // 前台
    // 'frontend' => [

        // 前台網址
        'url' => 'http://default.test',

        // 上傳資料夾
        'upload_path' => 'uploads/' . $http_host,

        // 資源路徑
        // 'assets_frontend_path' => 'assets/',

        // 無圖片時顯示的字串
        // 'upload_image_default_string' => '',
    // ],
]);
