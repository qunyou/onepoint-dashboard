<?php

namespace Onepoint\Dashboard\Controllers;

use Analytics;
use Spatie\Analytics\Period;
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
        // retrieve visitors and pageview data for the current day and the last seven days
        $analytics_page_view_seven_days = Analytics::fetchVisitorsAndPageViews(Period::days(7));
        $this->tpl_data['analytics_page_view_seven_days'] = $analytics_page_view_seven_days;

        // retrieve visitors and pageviews since the 6 months ago
        $analytics_page_view_six_months = Analytics::fetchVisitorsAndPageViews(Period::months(6));
        $this->tpl_data['analytics_page_view_six_months'] = $analytics_page_view_six_months;

        // retrieve sessions and pageviews with yearMonth dimension since 1 year ago 
        // $analyticsData = Analytics::performQuery(
        //     Period::years(1),
        //     'ga:sessions',
        //     [
        //         'metrics' => 'ga:sessions, ga:pageviews',
        //         'dimensions' => 'ga:yearMonth'
        //     ]
        // );

        $this->tpl_data['path_presenter'] = $path_presenter;
        $this->tpl_data['page_title'] = trans('backend.預設首頁');
        if (config('backend.use_browser_agent', false)) {

            // 總造訪人次
            $this->tpl_data['all_visitor'] = BrowserAgent::distinct('ip')->count('ip');
            
            // 本週不重複造訪人次
            // $start = date('Y-m-d',strtotime('last week'));
            $this_week = date('Y-m-d', strtotime('this week'));
            $today = date('Y-m-d');
            $this->tpl_data['this_week_visitor'] = BrowserAgent::whereBetween('created_at', [$this_week, $today])->distinct('ip')->count('ip');
    
            // 總瀏覽數
            $this->tpl_data['all_pages'] = BrowserAgent::count();
    
            // 本週瀏覽數
            $this->tpl_data['this_week_pages'] = BrowserAgent::whereBetween('created_at', [$this_week, $today])->count();
        }
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
        dd(storage_path('app/public'), public_path('storage'));
        // symlink(public_path('storage'), storage_path('app/public'));
        // ln -sr /home/vagrant/code/popupasia.com/private/storage/app/public /home/vagrant/code/popupasia.com/storage
        
        // 正常的目錄配置可使用此方法
        // Artisan::call('storage:link');
    }
}
