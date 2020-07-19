<?php
// 判斷載入設定檔路徑
$http_host = 'default';

// 單元測試時要用這個設定
$app_url = 'http://default.test';
if (isset($_SERVER['HTTP_HOST'])) {
    if (request()->isSecure()) {
        $ssl_protocol = 'https://';
    } else {
        $ssl_protocol = 'http://';
    }
    $app_url = $ssl_protocol . $_SERVER['HTTP_HOST'];
    switch ($_SERVER['HTTP_HOST']) {
        case 'default.test':
            $http_host = 'default';
            break;

        default:
            abort(404);
    }
} else {
    
    // 在 artisan 狀態
    $http_host = 'default';
}
config(['http_host' => $http_host]);
config(['filesystems.disks.public.url' => $app_url . '/storage']);

// 載入共用設定
include base_path('custom') . '/' . config('http_host') . '/baseConfig.php';