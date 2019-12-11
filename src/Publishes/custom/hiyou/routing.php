<?php
// 預設頁
Route::prefix('dashboard')->group(function () {
    Route::get('index', 'DashboardController@index');
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

// 文章分類
Route::prefix('article-category')->group(function () {
    Route::get('index', 'ArticleCategoryController@index');
    Route::put('index', 'ArticleCategoryController@putIndex');
    Route::get('update', 'ArticleCategoryController@update');
    Route::put('update', 'ArticleCategoryController@putUpdate');
    Route::get('duplicate', 'ArticleCategoryController@duplicate');
    Route::put('duplicate', 'ArticleCategoryController@putDuplicate');
    Route::get('detail', 'ArticleCategoryController@detail');
    Route::get('delete', 'ArticleCategoryController@delete');
});

// 文章
Route::prefix('article')->group(function () {
    Route::get('index', 'ArticleController@index');
    Route::put('index', 'ArticleController@putIndex');
    Route::get('update', 'ArticleController@update');
    Route::put('update', 'ArticleController@putUpdate');
    Route::get('duplicate', 'ArticleController@duplicate');
    Route::put('duplicate', 'ArticleController@putDuplicate');
    Route::get('detail', 'ArticleController@detail');
    Route::get('delete', 'ArticleController@delete');
    Route::get('import', 'ArticleController@import');
    Route::put('import', 'ArticleController@putImport');
});

// 新聞分類
Route::prefix('news-category')->group(function () {
    Route::get('index', 'NewsCategoryController@index');
    Route::put('index', 'NewsCategoryController@putIndex');
    Route::get('update', 'NewsCategoryController@update');
    Route::put('update', 'NewsCategoryController@putUpdate');
    Route::get('duplicate', 'NewsCategoryController@duplicate');
    Route::put('duplicate', 'NewsCategoryController@putDuplicate');
    Route::get('detail', 'NewsCategoryController@detail');
    Route::get('delete', 'NewsCategoryController@delete');
});

// 新聞
Route::prefix('news')->group(function () {
    Route::get('index', 'NewsController@index');
    Route::put('index', 'NewsController@putIndex');
    Route::get('update', 'NewsController@update');
    Route::put('update', 'NewsController@putUpdate');
    Route::get('duplicate', 'NewsController@duplicate');
    Route::put('duplicate', 'NewsController@putDuplicate');
    Route::get('detail', 'NewsController@detail');
    Route::get('delete', 'NewsController@delete');
    Route::get('import', 'NewsController@import');
    Route::put('import', 'NewsController@putImport');
});

// 檢視錯誤訊息
Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');