# 安裝
    composer require onepoint/dashboard

## 複製必要檔案至正確目錄
    php artisan vendor:publish --force

會出現選項，選擇這個項目

* Provider: Onepoint\Dashboard\DashboardServiceProvider

# 安裝必要 Package

## 縮圖 Package
    composer require intervention/image

### app.php 加入內容
#### providers
    Intervention\Image\ImageServiceProvider::class

#### aliases
    'Image' => Intervention\Image\Facades\Image::class

# 後台未登入導向修改
app/Http/Middleware/Authenticate.php

    return route('login');
    修改為
    return route(config('dashboard.uri'));

# 修改 user.php 路徑
config/auth.php

    'model' => App\User::class,
    修改為
    'model' => App\Entities\User::class,

## 建立軟連結
    php artisan storage:link
    在虛擬主機上可以用這個網址來建立(相關的 route 規則要打開)
    http://url/backend/dashboard/storage-link

## 修改設定
config/app.php

    'url' => env('APP_URL', 'http://localhost'),
    修改為
    'url' => config('app.url'),