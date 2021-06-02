<?php
// 後台
Route::namespace('Onepoint\Base\Controllers')->prefix(config('dashboard.uri'))->middleware(['web', 'auth'])->group(function () {

    Route::get('dashboard/index', 'DashboardController@index')->name('dashboard-index');

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
    });

    // 文章分類
    Route::group(['prefix' => 'article-category'], function () {
        Route::get('index', 'ArticleCategoryController@index');
        Route::put('index', 'ArticleCategoryController@putIndex');
        Route::get('update', 'ArticleCategoryController@update');
        Route::put('update', 'ArticleCategoryController@putUpdate');
        Route::get('detail', 'ArticleCategoryController@detail');
        Route::get('delete', 'ArticleCategoryController@delete');
        Route::get('duplicate', 'ArticleCategoryController@duplicate');
        Route::put('duplicate', 'ArticleCategoryController@putDuplicate');
        Route::get('rearrange', 'ArticleCategoryController@rearrange');
        Route::get('drag-sort', 'ArticleCategoryController@dragSort');
    });

    // 文章
    Route::group(['prefix' => 'article'], function () {
        Route::get('index', 'ArticleController@index');
        Route::put('index', 'ArticleController@putIndex');
        Route::get('update', 'ArticleController@update');
        Route::put('update', 'ArticleController@putUpdate');
        Route::get('detail', 'ArticleController@detail');
        Route::get('delete', 'ArticleController@delete');
        Route::get('import', 'ArticleController@import');
        Route::put('import', 'ArticleController@putImport');
        Route::get('duplicate', 'ArticleController@duplicate');
        Route::put('duplicate', 'ArticleController@putDuplicate');
        Route::get('rearrange', 'ArticleController@rearrange');
        Route::get('drag-sort', 'ArticleController@dragSort');
    });

    // 文章圖片
    Route::group(['prefix' => 'article-image'], function () {
        Route::get('index', 'ArticleImageController@index');
        Route::put('index', 'ArticleImageController@putIndex');
        Route::get('update', 'ArticleImageController@update');
        Route::put('update', 'ArticleImageController@putUpdate');
        Route::get('detail', 'ArticleImageController@detail');
        Route::get('delete', 'ArticleImageController@delete');
    });

    // 最新消息分類
    Route::group(['prefix' => 'news-category'], function () {
        Route::get('index', 'NewsCategoryController@index');
        Route::put('index', 'NewsCategoryController@putIndex');
        Route::get('update', 'NewsCategoryController@update');
        Route::put('update', 'NewsCategoryController@putUpdate');
        Route::get('detail', 'NewsCategoryController@detail');
        Route::get('delete', 'NewsCategoryController@delete');
    });

    // 最新消息
    Route::group(['prefix' => 'news'], function () {
        Route::get('index', 'NewsController@index');
        Route::put('index', 'NewsController@putIndex');
        Route::get('update', 'NewsController@update');
        Route::put('update', 'NewsController@putUpdate');
        Route::get('detail', 'NewsController@detail');
        Route::get('delete', 'NewsController@delete');
    });

    // 部落格分類
    Route::group(['prefix' => 'blog-category'], function () {
        Route::get('index', 'BlogCategoryController@index');
        Route::put('index', 'BlogCategoryController@putIndex');
        Route::get('update', 'BlogCategoryController@update');
        Route::put('update', 'BlogCategoryController@putUpdate');
        Route::get('detail', 'BlogCategoryController@detail');
        Route::get('delete', 'BlogCategoryController@delete');
    });

    // 部落格
    Route::group(['prefix' => 'blog'], function () {
        Route::get('index', 'BlogController@index');
        Route::put('index', 'BlogController@putIndex');
        Route::get('update', 'BlogController@update');
        Route::put('update', 'BlogController@putUpdate');
        Route::get('detail', 'BlogController@detail');
        Route::get('delete', 'BlogController@delete');
    });
});