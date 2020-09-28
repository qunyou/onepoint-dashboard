# 後台操作界面及基本權限功能

## 使用 Composer 安裝(方法一)
    執行
    composer require onepoint/dashboard

## 自訂安裝(方法二)
    將 packages 資料夾放在網站根目錄
    在 composer.json 加上
    "autoload": {
        "psr-4": {
            ...
            "Onepoint\\Dashboard\\": "packages/onepoint/dashboard/src",
        },
        ...
    },

    執行
    composer dump-autoload

> 以上兩個方法擇一使用

在 config/app.php 的 providers 加上

    Onepoint\Dashboard\DashboardServiceProvider::class,

### 複製必要檔案至正確目錄(檔案很多，執行要花一點時間)

    先刪除以下檔案
    database/migrations/2014_10_12_000000_create_users_table.php
    packages/onepoint/dashboard/src/Publishes/database/seeds/DatabaseSeeder.php

    執行
    php artisan vendor:publish --provider="Onepoint\Dashboard\DashboardServiceProvider"

## 設定檔位置設定
custom/httpHost.php

    範例名稱為 default，可視情況自行修改
    $http_host = 'default';

    如果 default 改成 onepoint 這個資料夾
    custom/default
    就要改成
    custom/onepoint

    修改成實際運作的網址，主要是用來判斷不同網址載入不同設定檔，如果不需要不同網址，可以取消 switch 的判斷
    如果網址是 6k6.test
    就要將
    case 'default.test':
    改成
    case '6k6.test':
    如果沒有取消 switch 的判斷，目前的網址不包含在 case 中，就會顯示 404 頁面

## 引入設定檔

    將以下內容放在 config/app.php 的第一行
    // 依網址判斷資料庫、資料夾等設定，產生 config('http_host') 設定值
    include base_path('custom') . '/httpHost.php';

## 資料庫設定

custom/default/baseConfig.php

在這個檔設定資料庫的帳號密碼

建立資料庫，建立時選擇utf8mb4編碼的資料庫

執行以下指令重新產生 Composer 的自動讀取檔案列表，以免執行 seed 指令時找不到檔案

    composer dump-autoload

先刪除預設的 user migrate 檔案

    database/migrations/2014_10_12_000000_create_users_table.php

執行以下指令建立預設的資料表及預設資料，執行前先修改 .env 中的資料庫帳號密碼，在 artisan 中不會去讀取 custom/default/baseConfig.php 設定的資料庫帳號密碼。

    php artisan migrate --seed

清空資料庫，重新建立預設資料(想要重設資料庫才需要執行的指令)

    php artisan migrate:refresh --seed

config/database.php

    connections.mysql.strict 要改成 false

## 安裝必要 Package

### 縮圖 Package
    composer require intervention/image

#### app.php 加入內容
##### providers
    Intervention\Image\ImageServiceProvider::class

##### aliases
    'Image' => Intervention\Image\Facades\Image::class

#### 執行
    php artisan vendor:publish --provider="Intervention\Image\ImageServiceProviderLaravelRecent"

### excel Package
    composer require maatwebsite/excel

#### app.php 加入內容
##### providers
    Maatwebsite\Excel\ExcelServiceProvider::class,

##### aliases
    'Excel' => Maatwebsite\Excel\Facades\Excel::class,

#### 發佈套件檔案至正確目錄
    php artisan vendor:publish --provider="Maatwebsite\Excel\ExcelServiceProvider"

### 檢視 Log Package
    composer require rap2hpoutre/laravel-log-viewer

#### app.php 加入內容
##### providers
    Rap2hpoutre\LaravelLogViewer\LaravelLogViewerServiceProvider::class,

### 編輯器檔案管理 Package
    composer require unisharp/laravel-filemanager

#### app.php 加入內容
##### providers
    UniSharp\LaravelFilemanager\LaravelFilemanagerServiceProvider::class,

#### 發佈 Package 必要檔案
    php artisan vendor:publish --tag=lfm_config
    php artisan vendor:publish --tag=lfm_public
    php artisan route:clear
    php artisan config:clear

#### 流量統計套件
    composer require jenssegers/agent

#### app.php 加入內容
##### providers
    Jenssegers\Agent\AgentServiceProvider::class,

##### aliases
    'Agent' => Jenssegers\Agent\Facades\Agent::class,

## 認證相關設定

### 修改 user.php 路徑
config/auth.php

    'model' => App\User::class,
    修改為
    'model' => Onepoint\Dashboard\Entities\User::class,

    如果前台有要做會員登入，在 guards 新增元素
    'member' => [
        'driver' => 'session',
        'provider' => 'members',
    ],

    在 providers 新增元素
    'members' => [
        'driver' => 'eloquent',
        'model' => Onepoint\Reading\Entities\Member::class,
    ],

    在 passwords 新增元素
    'members' => [
        'provider' => 'members',
        'table' => 'password_resets',
        'expire' => 15,
    ],

    在 app/Http/Kernel.php 增加登入檢查方法
    protected $routeMiddleware = [
        ...

        // Guard 登入檢查
        'auth.guard' => \App\Http\Middleware\AuthenticateGuard::class,
    ];

## 上傳檔案相關設定

### 建立軟連結
    php artisan storage:link
    在虛擬主機上可以用這個網址來建立(相關的 route 規則要打開)
    http://url/backend/dashboard/storage-link

    主程式放在 public 底下的 private 時使用的方法 storage:link 要使用這個方法
    ln -s private/storage/app/public storage

    預設目錄不是 public 的時候，要修改這個檔案
    app/Providers/AppServiceProvider.php
    public function register()
    {
        public 改名為 public_html 的方法
        $this->app->bind('path.public', function() {
            return base_path('../public_html');
        });

        主程式放在 public 底下的 private 時使用的方法
        $this->app->bind('path.public', function() {
            return base_path('../public_html');
        });
    }

    public function boot()
    {
        // 強制使用 https
        \URL::forceScheme('https');
    }

### 修改設定
config/app.php

    'url' => env('APP_URL', 'http://localhost'),
    修改為
    'url' => config('app.url'),

    'timezone' => 'UTC',
    修改為
    'timezone' => 'Asia/Taipei',

config/filesystems.php

    'public' => [
        storage_path('app/public')
        'url' => env('APP_URL').'/storage',
        修改為
        'url' => config('app.url').'/storage',
    ],

.env

    APP_DEBUG=false

### Homestead 上傳檔案大小修改

預設只能傳小於 1M 的檔案，修改後會比較好測試

    在設定檔中的 http 中加一行上傳檔案大小設定
    sudo nano /etc/nginx/nginx.conf
    http {
        client_max_body_size 100M;
    }

    預設可上傳 100M, 正常情況不用修改
    sudo nano /etc/php/7.4/fpm/php.ini

    要修改的項目
    memory_limit = 128M 
    post_max_size = 20M
    upload_max_filesize = 10M

### 404 轉跳首頁

app/Exceptions/Handler.php

    NotFoundHttpException 使用物件
    use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

    public function render($request, Exception $exception)
    {
        if ($exception instanceof NotFoundHttpException) {
            return redirect()->route('home');
        }
    }