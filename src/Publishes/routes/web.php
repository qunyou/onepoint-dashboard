<?php
// 依網址判斷資料庫、資料夾等設定，產生 config('http_host') 設定值
include base_path('custom') . '/httpHost.php';

// 載入共用設定
include base_path('custom') . '/' . config('http_host') . '/baseConfig.php';

// 認證
Route::prefix(config('dashboard.uri'))->group(function () {
    config(['backend_url_suffix' => request()->segment(1)]);

    // 登入頁
    Route::get('/', 'AuthController@login')->name(config('dashboard.uri'));
    Route::get('login', 'AuthController@login')->name('login');
    Route::post('login', 'AuthController@postLogin');
    Route::get('logout', 'AuthController@logout');
    Route::get('reset', 'AuthController@reset');

    // 登入後頁面
    Route::middleware(['auth'])->group(function () {

        // 載入後端設定
        include base_path('custom') . '/' . config('http_host') . '/backendConfig.php';

        // 載入後端 routes
        include base_path('custom') . '/' . config('http_host') . '/routing.php';
    });
});

// 載入前端 routes
include base_path('custom') . '/' . config('http_host') . '/routingFrontend.php';