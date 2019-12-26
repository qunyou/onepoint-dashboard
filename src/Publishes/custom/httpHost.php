<?php
// 判斷載入設定檔路徑
$http_host = 'default';
$app_url = 'http://default.test';

// 單元測試時要用這個設定
// $app_url = 'http://localhost';
if (isset($_SERVER['HTTP_HOST'])) {
    $app_url = $_SERVER['HTTP_HOST'];
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