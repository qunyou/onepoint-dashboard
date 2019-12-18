<?php
// 判斷載入設定檔路徑
$http_host = 'hiyou';
$app_url = 'http://localhost';
if (isset($_SERVER['HTTP_HOST'])) {
    $app_url = $_SERVER['HTTP_HOST'];
    switch ($_SERVER['HTTP_HOST']) {
        case 'hiyou.test':
            $http_host = 'hiyou';
            break;

        default:
            abort(404);
    }
} else {
    
    // 在 artisan 狀態
    $http_host = 'hiyou';
}
config(['http_host' => $http_host]);