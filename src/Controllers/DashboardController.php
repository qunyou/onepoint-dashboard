<?php

namespace Onepoint\Dashboard\Controllers;

use App\Http\Controllers\Controller;
use Artisan;
use Onepoint\Base\Entities\Article;
use Onepoint\Base\Entities\Blog;
use Onepoint\Base\Entities\BrowserAgent;
// use Onepoint\Base\Entities\InteriorDesign;
use Onepoint\Dashboard\Presenters\PathPresenter;
use Onepoint\Dashboard\Services\BaseService;
use Onepoint\Dashboard\Traits\ShareMethod;

/**
 * 登入預設頁
 */
class DashboardController extends Controller
{
    use ShareMethod;

    /**
     * 建構子
     */
    public function __construct(BaseService $base_services)
    {
        $this->share();
        $this->base_services = $base_services;
        $this->tpl_data = $base_services->tpl_data;
        $this->tpl_data['base_services'] = $this->base_services;
        $this->tpl_data['navigation_item'] = config('backend.navigation_item');

        // 預設網址
        // $this->uri = config('dashboard.uri') . 'dashboard/';
        // $this->tpl_data['uri'] = $this->uri;

        $this->view_path = 'dashboard::dashboard.pages.dashboard.';
        $this->uri = config('dashboard.uri') . '/';
        $this->tpl_data['uri'] = $this->uri;
    }

    /**
     * 首頁
     */
    public function index(PathPresenter $path_presenter)
    {
        $this->tpl_data['path_presenter'] = $path_presenter;
        $this->tpl_data['page_title'] = trans('backend.預設首頁');
        // $this->tpl_data['browser_agent'] = BrowserAgent::all();
        // $this->tpl_data['browser_agent_count'] = BrowserAgent::count();
        // $this->tpl_data['blog_count'] = Blog::count();
        // $this->tpl_data['article_count'] = Article::count();
        // $this->tpl_data['interior_design_count'] = InteriorDesign::count();
        return view($this->view_path . 'index', $this->tpl_data);
    }

    /**
     * 流量統計
     */
    public function browserAgent(PathPresenter $path_presenter)
    {
        $this->tpl_data['path_presenter'] = $path_presenter;
        $this->tpl_data['page_title'] = trans('backend.預設首頁');
        $this->tpl_data['browser_agent'] = BrowserAgent::all();
        return view($path_presenter->backend_view('pages.dashboard.browser-agent'), $this->tpl_data);
    }

    /**
     * 建立軟連結(symbolic link)
     *
     * 如果 public 下已經有 storage symbolic link 執行這個方法會出錯，要先刪除
     */
    public function storageLink()
    {
        // 顯示正確的路徑
        // dd(storage_path('app/public'), public_path('storage'));
        // symlink(public_path('storage'), storage_path('app/public'));
        // ln -sr /home/vagrant/code/popupasia.com/private/storage/app/public /home/vagrant/code/popupasia.com/storage
        
        // 正常的目錄配置可使用此方法
        Artisan::call('storage:link');
    }
}
