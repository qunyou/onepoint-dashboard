<?php
// 依網址判斷資料庫、資料夾等設定，產生 config('http_host') 設定值
include base_path('custom') . '/httpHost.php';

// 載入共用設定
include base_path('custom') . '/' . config('http_host') . '/baseConfig.php';

// 認證
// Route::prefix(config('dashboard.uri'))->group(function () {
Route::prefix(config('dashboard.uri'))->namespace('Onepoint\Dashboard\Controllers')->middleware(['web'])->group(function () {
    // config(['backend_url_suffix' => request()->segment(1)]);

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
        // include base_path('custom') . '/' . config('http_host') . '/routing.php';

        Route::prefix('dashboard')->group(function () {
            
            // 預設頁
            Route::get('index', 'DashboardController@index')->name('dashboard-index');
            Route::get('storage-link', 'DashboardController@storageLink');
        });

        // 網站設定
        Route::prefix('setting')->group(function () {
            Route::get('index', 'SettingController@index');
            Route::put('index', 'SettingController@putIndex');
            Route::get('update', 'SettingController@update');
            Route::put('update', 'SettingController@putUpdate');
            Route::get('detail', 'SettingController@detail');
            Route::get('delete', 'SettingController@delete');
            Route::get('model', 'SettingController@model');
            Route::put('model', 'SettingController@putModel');
            Route::get('model-update', 'SettingController@modelUpdate');
            Route::put('model-update', 'SettingController@putModelUpdate');
            Route::get('model-detail', 'SettingController@modelDetail');
        });

        // 人員管理
        Route::prefix('user')->group(function () {
            Route::get('index', 'UserController@index');
            Route::put('index', 'UserController@putIndex');
            Route::get('update', 'UserController@update');
            Route::put('update', 'UserController@putUpdate');
            Route::get('duplicate', 'UserController@duplicate');
            Route::put('duplicate', 'UserController@putDuplicate');
            Route::get('detail', 'UserController@detail');
            Route::get('delete', 'UserController@delete');

            // 個人資料維護
            Route::get('profile', 'UserController@profile');
            Route::put('profile', 'UserController@putProfile');

            // 匯入
            // Route::get('import', 'UserController@import');
            // Route::put('import', 'UserController@putImport');
        });

        // 人員群組
        Route::prefix('role')->group(function () {
            Route::get('index', 'RoleController@index');
            Route::put('index', 'RoleController@putIndex');
            Route::get('update', 'RoleController@update');
            Route::put('update', 'RoleController@putUpdate');
            Route::get('duplicate', 'RoleController@duplicate');
            Route::put('duplicate', 'RoleController@putDuplicate');
            Route::get('detail', 'RoleController@detail');
            Route::get('delete', 'RoleController@delete');
            Route::get('apply-version', 'RoleController@applyVersion');
        });

        // 檢視錯誤訊息
        Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
    });
});