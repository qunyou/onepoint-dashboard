<?php

namespace Onepoint\Base\Controllers;

use Analytics;
use App\Http\Controllers\Controller;
use Onepoint\Base\Entities\BrowserAgent;
use Onepoint\Dashboard\Presenters\PathPresenter;
use Onepoint\Dashboard\Traits\ShareMethod;
use Spatie\Analytics\Period;

/**
 * 登入預設頁
 */
class DashboardController extends Controller
{
    use ShareMethod;

    /**
     * 建構子
     */
    public function __construct()
    {
        $this->share();

        // 預設網址
        $this->uri = config('dashboard.uri') . '/dashboard/';
        $this->tpl_data['uri'] = $this->uri;

        // view 路徑
        $this->view_path = 'base::' . config('dashboard.view_path') . '.pages.dashboard.';
        $this->product_id = request('product_id', false);
    }

    /**
     * 首頁
     */
    public function index(PathPresenter $path_presenter)
    {
        $component_datas = $this->listPrepare();
        $permission_controller_string = get_class($this);
        $component_datas['permission_controller_string'] = $permission_controller_string;
        $component_datas['uri'] = $this->uri;

        // GA
        if (config('backend.analytics.enable', false)) {

            // Visitors And PageViews
            // $analytics_visitor_pageviews = Analytics::fetchVisitorsAndPageViews(Period::days(7));
            // $this->tpl_data['analytics_visitor_pageviews'] = $analytics_visitor_pageviews;
    
            // retrieve visitors and pageviews since the 6 months ago
            // $analytics_page_view_six_months = Analytics::fetchVisitorsAndPageViews(Period::months(6));
            // $this->tpl_data['analytics_page_view_six_months'] = $analytics_page_view_six_months;
    
            // Total visitors and pageviews
            $analytics_total_visitor = Analytics::fetchTotalVisitorsAndPageViews(Period::days(7));
            $this->tpl_data['analytics_total_visitor'] = $analytics_total_visitor;
    
            // Most visited pages
            $analytics_most_visited_pages = Analytics::fetchMostVisitedPages(Period::days(7), 10);
            $this->tpl_data['analytics_most_visited_pages'] = $analytics_most_visited_pages;
    
            // Top referrers
            // url, pageViews
            $analytics_top_referrers = Analytics::fetchTopReferrers(Period::days(7), 10);
            $this->tpl_data['analytics_top_referrers'] = $analytics_top_referrers;
    
            // User Types
            // type, sessions
            $analytics_user_types = Analytics::fetchUserTypes(Period::days(7));
            $this->tpl_data['analytics_user_types'] = $analytics_user_types;
    
            // top browsers
            // browser, sessions
            // $analytics_top_browsers = Analytics::fetchTopBrowsers(Period::days(7), 10);
            // $this->tpl_data['analytics_top_browsers'] = $analytics_top_browsers;
            // dd($analytics_top_browsers);
    
            //retrieve sessions and pageviews with yearMonth dimension since 1 year ago
            // $analyticsData = Analytics::performQuery(
            //     Period::years(1),
            //     'ga:sessions',
            //     [
            //         'metrics' => 'ga:sessions, ga:pageviews',
            //         'dimensions' => 'ga:yearMonth'
            //     ]
            // );
        }

        // 流量統計
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

        // 部落格
        // $this->tpl_data['blog_count'] = Blog::count();

        // 文章
        // $this->tpl_data['article_count'] = Article::count();

        // 室內設計作品
        // $this->tpl_data['interior_design_count'] = InteriorDesign::count();
        $this->tpl_data['component_datas'] = $component_datas;
        return view($this->view_path . 'index', $this->tpl_data);
    }

    /**
     * 流量統計
     */
    public function browserAgent(PathPresenter $path_presenter)
    {
        $this->tpl_data['path_presenter'] = $path_presenter;
        $this->tpl_data['page_title'] = trans('dashboard::backend.預設首頁');
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
