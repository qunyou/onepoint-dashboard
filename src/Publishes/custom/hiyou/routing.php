<?php
// 預設頁
Route::prefix('dashboard')->group(function () {
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
});

// 連結分類
Route::prefix('resource-category')->group(function () {
    Route::get('index', 'ResourceCategoryController@index');
    Route::put('index', 'ResourceCategoryController@putIndex');
    Route::get('update', 'ResourceCategoryController@update');
    Route::put('update', 'ResourceCategoryController@putUpdate');
    Route::get('duplicate', 'ResourceCategoryController@duplicate');
    Route::put('duplicate', 'ResourceCategoryController@putDuplicate');
    Route::get('detail', 'ResourceCategoryController@detail');
    Route::get('delete', 'ResourceCategoryController@delete');
});

// 連結
Route::prefix('resource')->group(function () {
    Route::get('index', 'ResourceController@index');
    Route::put('index', 'ResourceController@putIndex');
    Route::get('update', 'ResourceController@update');
    Route::put('update', 'ResourceController@putUpdate');
    Route::get('duplicate', 'ResourceController@duplicate');
    Route::put('duplicate', 'ResourceController@putDuplicate');
    Route::get('detail', 'ResourceController@detail');
    Route::get('delete', 'ResourceController@delete');
});

// 下載分類
Route::prefix('download-category')->group(function () {
    Route::get('index', 'DownloadCategoryController@index');
    Route::put('index', 'DownloadCategoryController@putIndex');
    Route::get('update', 'DownloadCategoryController@update');
    Route::put('update', 'DownloadCategoryController@putUpdate');
    Route::get('duplicate', 'DownloadCategoryController@duplicate');
    Route::put('duplicate', 'DownloadCategoryController@putDuplicate');
    Route::get('detail', 'DownloadCategoryController@detail');
    Route::get('delete', 'DownloadCategoryController@delete');
});

// 下載
Route::prefix('download')->group(function () {
    Route::get('index', 'DownloadController@index');
    Route::put('index', 'DownloadController@putIndex');
    Route::get('update', 'DownloadController@update');
    Route::put('update', 'DownloadController@putUpdate');
    Route::get('duplicate', 'DownloadController@duplicate');
    Route::put('duplicate', 'DownloadController@putDuplicate');
    Route::get('detail', 'DownloadController@detail');
    Route::get('delete', 'DownloadController@delete');
});

// 相簿分類
Route::prefix('album-category')->group(function () {
    Route::get('index', 'AlbumCategoryController@index');
    Route::put('index', 'AlbumCategoryController@putIndex');
    Route::get('update', 'AlbumCategoryController@update');
    Route::put('update', 'AlbumCategoryController@putUpdate');
    Route::get('duplicate', 'AlbumCategoryController@duplicate');
    Route::put('duplicate', 'AlbumCategoryController@putDuplicate');
    Route::get('detail', 'AlbumCategoryController@detail');
    Route::get('delete', 'AlbumCategoryController@delete');
});

// 相簿
Route::prefix('album')->group(function () {
    Route::get('index', 'AlbumController@index');
    Route::put('index', 'AlbumController@putIndex');
    Route::get('update', 'AlbumController@update');
    Route::put('update', 'AlbumController@putUpdate');
    Route::get('duplicate', 'AlbumController@duplicate');
    Route::put('duplicate', 'AlbumController@putDuplicate');
    Route::get('detail', 'AlbumController@detail');
    Route::get('delete', 'AlbumController@delete');
});

// 相片
Route::prefix('album-image')->group(function () {
    Route::get('index', 'AlbumImageController@index');
    Route::put('index', 'AlbumImageController@putIndex');
    Route::get('update', 'AlbumImageController@update');
    Route::put('update', 'AlbumImageController@putUpdate');
    Route::get('duplicate', 'AlbumImageController@duplicate');
    Route::put('duplicate', 'AlbumImageController@putDuplicate');
    Route::get('detail', 'AlbumImageController@detail');
    Route::get('delete', 'AlbumImageController@delete');
});

// 單頁
Route::prefix('page')->group(function () {
    Route::get('index', 'PageController@index');
    Route::put('index', 'PageController@putIndex');
    Route::get('update', 'PageController@update');
    Route::put('update', 'PageController@putUpdate');
    Route::get('duplicate', 'PageController@duplicate');
    Route::put('duplicate', 'PageController@putDuplicate');
    Route::get('detail', 'PageController@detail');
    Route::get('delete', 'PageController@delete');
});

// 檢視錯誤訊息
Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

// 單位
// Route::namespace('Reading')->prefix('department')->group(function () {
//     Route::get('index', 'DepartmentController@index');
//     Route::put('index', 'DepartmentController@putIndex');
//     Route::get('update', 'DepartmentController@update');
//     Route::put('update', 'DepartmentController@putUpdate');
//     Route::get('detail', 'DepartmentController@detail');
//     Route::get('delete', 'DepartmentController@delete');
// });

// 試卷領域
// Route::namespace('Reading')->prefix('examination-field')->group(function () {
//     Route::get('index', 'ExaminationFieldController@index');
//     Route::put('index', 'ExaminationFieldController@putIndex');
//     Route::get('update', 'ExaminationFieldController@update');
//     Route::put('update', 'ExaminationFieldController@putUpdate');
//     Route::get('detail', 'ExaminationFieldController@detail');
//     Route::get('delete', 'ExaminationFieldController@delete');
// });

// IP控管
// Route::namespace('Reading')->prefix('ip-list')->group(function () {
//     Route::get('index', 'IpListController@index');
//     Route::put('index', 'IpListController@putIndex');
//     Route::get('update', 'IpListController@update');
//     Route::put('update', 'IpListController@putUpdate');
//     Route::get('detail', 'IpListController@detail');
//     Route::get('delete', 'IpListController@delete');
// });

// 讀者
// Route::namespace('Reading')->prefix('reader')->group(function () {
//     Route::get('index', 'ReaderController@index');
//     Route::put('index', 'ReaderController@putIndex');
//     Route::get('update', 'ReaderController@update');
//     Route::put('update', 'ReaderController@putUpdate');
//     Route::get('detail', 'ReaderController@detail');
//     Route::get('delete', 'ReaderController@delete');
// });

// 讀者類型
// Route::namespace('Reading')->prefix('reader-category')->group(function () {
//     Route::get('index', 'ReaderCategoryController@index');
//     Route::put('index', 'ReaderCategoryController@putIndex');
//     Route::get('update', 'ReaderCategoryController@update');
//     Route::put('update', 'ReaderCategoryController@putUpdate');
//     Route::get('detail', 'ReaderCategoryController@detail');
//     Route::get('delete', 'ReaderCategoryController@delete');
// });

// 試卷
// Route::namespace('Reading')->prefix('examination')->group(function () {
//     Route::get('index', 'ExaminationController@index');
//     Route::put('index', 'ExaminationController@putIndex');
//     Route::get('update', 'ExaminationController@update');
//     Route::put('update', 'ExaminationController@putUpdate');
//     Route::get('detail', 'ExaminationController@detail');
//     Route::get('delete', 'ExaminationController@delete');
// });

// 試題
// Route::namespace('Reading')->prefix('book-question')->group(function () {
//     Route::get('index', 'BookQuestionController@index');
//     Route::put('index', 'BookQuestionController@putIndex');
//     Route::get('update', 'BookQuestionController@update');
//     Route::put('update', 'BookQuestionController@putUpdate');
//     Route::get('detail', 'BookQuestionController@detail');
//     Route::get('delete', 'BookQuestionController@delete');

//     // 匯入
//     Route::get('import', 'BookQuestionController@import');
//     Route::put('import', 'BookQuestionController@putImport');
// });

// 試卷測驗結果
// Route::namespace('Reading')->prefix('examination-result')->group(function () {
//     Route::get('index', 'ExaminationResultController@index');
//     Route::put('index', 'ExaminationResultController@putIndex');
//     // Route::get('update', 'ExaminationResultController@update');
//     // Route::put('update', 'ExaminationResultController@putUpdate');
//     Route::get('detail', 'ExaminationResultController@detail');
//     Route::get('delete', 'ExaminationResultController@delete');
// });