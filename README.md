# 安裝
    composer require onepoint/dashboard

在 config/app.php 的 providers 加上

    Onepoint\Dashboard\DashboardServiceProvider::class,

## 複製必要檔案至正確目錄

    php artisan vendor:publish --force

會出現選項，選擇這個項目

* Provider: Onepoint\Dashboard\DashboardServiceProvider

# 設定檔位置設定
custom/httpHost.php

    範例名稱為 hiyou，可視情況自行修改
    $http_host = 'hiyou';

    如果 hiyou 改成 onepoint，這個資料夾
    custom/hiyou
    就要改成
    custom/onepoint

    修改成實際運作的網址，主要是用來判斷不同網址載入不同設定檔，如果不需要不同網址，可以取消 switch 的判斷
    case 'hiyou.test':

# 資料庫設定

custom/hiyou/baseConfig.php

在這個檔設定資料庫的帳號密碼

建立資料庫，建立時選擇utf8mb4編碼的資料庫

執行以下指令建立預設的資料表及預設資料

    php artisan migrate --seed

清空資料庫，重新建立預設資料

    php artisan migrate:refresh --seed

config/database.php

    connections.mysql.strict 要改成 false

# 安裝必要 Package

## 縮圖 Package
    composer require intervention/image

### app.php 加入內容
#### providers
    Intervention\Image\ImageServiceProvider::class

#### aliases
    'Image' => Intervention\Image\Facades\Image::class

# 認證相關設定

## 修改 user.php 路徑
config/auth.php

    'model' => App\User::class,
    修改為
    'model' => App\Entities\User::class,

# 上傳檔案相關設定

## 建立軟連結
    php artisan storage:link
    在虛擬主機上可以用這個網址來建立(相關的 route 規則要打開)
    http://url/backend/dashboard/storage-link

## 修改網址設定
config/app.php

    'url' => env('APP_URL', 'http://localhost'),
    修改為
    'url' => config('app.url'),