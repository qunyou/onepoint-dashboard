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
        Route::get('duplicate', 'ArticleController@duplicate');
        Route::put('duplicate', 'ArticleController@putDuplicate');
        Route::get('detail', 'ArticleController@detail');
        Route::get('delete', 'ArticleController@delete');
        Route::get('rearrange', 'ArticleController@rearrange');
        Route::get('drag-sort', 'ArticleController@dragSort');

        // 刪除圖片
        Route::get('delete-image/{image_id}', 'ArticleController@deleteImage');

        // 上傳圖片
        Route::get('multiple', 'ArticleController@multiple');
        Route::post('multiple', 'ArticleController@postMultiple');

        // 圖片排序
        Route::post('image-sort', 'ArticleController@imageSort');

        // 文章附檔
        Route::get('attachment-update/{attachment_id}', 'ArticleController@attachmentUpdate');
        Route::put('attachment-update/{attachment_id}', 'ArticleController@putAttachmentUpdate');
        Route::get('attachment-add', 'ArticleController@attachmentAdd');
        Route::put('attachment-add', 'ArticleController@putAttachmentAdd');
        Route::get('attachment-delete/{attachment_id}', 'ArticleController@attachmentDelete');
        Route::get('attachment-download/{attachment_id}', 'ArticleController@attachmentDownload');
    });
});