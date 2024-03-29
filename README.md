onepoint/dashboard
===

##### 後台操作界面及基本權限功能

---

安裝 laravel
---

直接從 Github 下載
[Laravel Github](https://github.com/laravel/laravel)

一些提示步驟

	composer install
	複製 .env 檔案
	php artisan key:generate
	
Homestead

	laravel new 專案資料夾名稱

使用 Composer 安裝 onepoint/dashboard
---

執行

    composer require onepoint/dashboard

在 config/app.php 的 providers 加上

    Onepoint\Dashboard\DashboardServiceProvider::class,

複製必要檔案至正確目錄
---

先刪除此檔案

	rm database/migrations/2014_10_12_000000_create_users_table.php

執行

    php artisan vendor:publish --provider="Onepoint\Dashboard\DashboardServiceProvider"

解壓縮vendor.zip(tinymce檔案管理界面用檔案)

	unzip public/vendor.zip
	rm public/vendor.zip

資料庫設定
---

#### 在這個檔設定資料庫的帳號密碼

1. 將 app/Providers/AppServiceProviderSample.php 的內容複製至 app/Providers/AppServiceProvider.php
2. 刪除 app/Providers/AppServiceProviderSample.php
3. 修改 app/Providers/AppServiceProvider.php 內的資料庫名稱密碼等資訊
4. 修改 .env 中的資料庫帳號密碼

#### 建立資料庫，建立時選擇 utf8mb4 編碼的資料庫

執行以下指令重新產生 Composer 的自動讀取檔案列表，以免執行 seed 指令時找不到檔案

    composer dump-autoload

加入內容 database/seeders/DatabaseSeeder.php

    public function run()
    {
        ...
        $this->call([
            UsersTableSeeder::class,
            RolesTableSeeder::class,
            RoleUsersTableSeeder::class,
            SettingsTableSeeder::class,
        ]);
    }

執行以下指令建立預設的資料表及預設資料，執行前先修改 .env 中的資料庫帳號密碼，在 artisan 中不會去讀取 app/Providers/AppServiceProvider.php 設定的資料庫帳號密碼。

    php artisan migrate --seed

清空資料庫，重新建立預設資料(想要重設資料庫才需要執行的指令)

    php artisan migrate:refresh --seed

修改 config/auth.php

    將 user model 改成自訂的 user model
    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => Onepoint\Base\Entities\User::class,
        ],
        ...
    ],

config/database.php

    connections.mysql.strict 要改成 false

安裝必要 Package
---

#### 文章管理功能

    在前面 publish 步驟時已建立 packages 資料夾，裡面有一個 base 套件

    composer.json 加上
    "autoload": {
        "psr-4": {
            …
            "Onepoint\\Base\\": "packages/onepoint/base/src"
        }
    },

    執行
    composer dump-autoload

    在 config/app.php 加上
    'providers' => [
        …
        Onepoint\Base\BaseServiceProvider::class,
    ]

    如果前台也要使用 package 的方式，可以參照 base 的方式建立資料夾，分別在 composer.json 及 config/app.php 加內容

#### 縮圖 Package

    composer require intervention/image

    // app.php 加入內容
    'providers' => [
        ...
        Intervention\Image\ImageServiceProvider::class
    ]

    'aliases' => [
        ...
        'Image' => Intervention\Image\Facades\Image::class
    ]

    // 執行
    php artisan vendor:publish --provider="Intervention\Image\ImageServiceProviderLaravelRecent"

#### excel Package

    composer require maatwebsite/excel

    // app.php 加入內容
    'providers' => [
        ...
        Maatwebsite\Excel\ExcelServiceProvider::class,
    ]

    'aliases' => [
        ...
        'Excel' => Maatwebsite\Excel\Facades\Excel::class,
    ]

    // 執行
    php artisan vendor:publish --provider="Maatwebsite\Excel\ExcelServiceProvider"

#### 檢視 Log Package

    composer require rap2hpoutre/laravel-log-viewer

    // app.php 加入內容
    'providers' => [
        ...
        Rap2hpoutre\LaravelLogViewer\LaravelLogViewerServiceProvider::class,
    ]

    // 在 route 檔案中增加規則
    Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

#### 編輯器檔案管理 Package

    COMPOSER_MEMORY_LIMIT=-1 composer require unisharp/laravel-filemanager

    // app.php 加入內容
    'providers' => [
        ...
        UniSharp\LaravelFilemanager\LaravelFilemanagerServiceProvider::class,
    ]

    // 執行
    php artisan vendor:publish --tag=fm-config
    php artisan vendor:publish --tag=fm-assets

認證相關設定
---

#### 修改 user.php 路徑

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
        'auth.guard' => \Onepoint\Dashboard\Middleware\AuthenticateGuard::class,
    ];

上傳檔案相關設定
---

#### 建立軟連結
    php artisan storage:link
    在虛擬主機上可以用這個網址來建立(相關的 route 規則要打開)
    http://url/backend/dashboard/storage-link

    預設目錄不是 public 的時候，要修改這個檔案
    app/Providers/AppServiceProvider.php
    public function register()
    {
        $this->app->bind('path.public', function() {
            return base_path('../public_html');

            主程式放在 public 底下的 private 時使用的方法
            return $_SERVER['DOCUMENT_ROOT'];
        });
    }

    public function boot()
    {
        // 強制使用 https
        \URL::forceScheme('https');
    }

#### 修改設定
config/app.php

    'url' => env('APP_URL', 'http://localhost'),
    修改為
    'url' => APP_URL,

    'timezone' => 'UTC',
    修改為
    'timezone' => 'Asia/Taipei',

config/filesystems.php

    'public' => [
        'url' => env('APP_URL').'/storage',
        修改為
        'url' => config('app.url').'/storage',
    ],

.env

    APP_DEBUG=false

Homestead 上傳檔案大小修改
---

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

Homestead 修改 Mysql 時區
---

    cd /etc/mysql/mysql.conf.d
    sudo nano mysqld.cnf

    在 [mysqld] 區塊下面增加一行
    default-time-zone='+08:00'

404 轉跳首頁
---

app/Exceptions/Handler.php

    NotFoundHttpException 使用物件
    use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

    public function render($request, Exception $exception)
    {
        if ($exception instanceof NotFoundHttpException) {
            // return redirect()->route('home');
            return redirect('index');
        }

        if ($exception instanceof \Illuminate\Session\TokenMismatchException) {
            return redirect('index');
        }
        return parent::render($request, $exception);
    }
