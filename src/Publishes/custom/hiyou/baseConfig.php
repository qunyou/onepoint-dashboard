<?php
if (isset($_SERVER['HTTP_HOST'])) {
    switch ($_SERVER['HTTP_HOST']) {
        case 'hiyou.test':
            $mysql_database = 'hiyou';
            $mysql_username = 'homestead';
            $mysql_password = 'secret';
            break;
        default:
            $mysql_database = 'skjhs';
            $mysql_username = 'skjhs';
            $mysql_password = 'lIin^UaJeg&#';
	}
} else {

    // 在 artisan 狀態
    $mysql_database = 'hiyou';
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
    'app.name' => '國小課後輔導報名系統',
    'app.url' => $app_url,
    'http_host' => $http_host,

    'dashboard' => [
        'layout_file' => 'dashboard',
        'view_path' => 'dashboard',
        'uri' => 'backend',

        // 登入後預設首頁
        'login_default_uri' => 'dashboard/index',
    ],

    // 前台
    'frontend' => [

        // 前台網址
        'url' => 'http://hiyou.test',

        // 上傳資料夾
        'upload_path' => 'uploads/' . $http_host,

        // 無圖片時顯示的字串
        // 'upload_image_default_string' => '',
    ],
]);
