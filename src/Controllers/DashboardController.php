<?php

namespace Onepoint\Dashboard\Controllers;

use DB;
use Analytics;
use App\Http\Controllers\Controller;
use Onepoint\Base\Entities\BrowserAgent;
use Onepoint\Dashboard\Presenters\PathPresenter;
use Onepoint\Dashboard\Services\BaseService;
use Onepoint\Dashboard\Traits\ShareMethod;
use Spatie\Analytics\Period;
use Onepoint\Base\Entities\OrderForm;
use Onepoint\Base\Entities\OrderItem;
use Onepoint\Base\Entities\Member;

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
        // $this->tpl_data = $base_services->tpl_data;
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
            // url, pageViews
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

        // 昨日訂單累計總金額
        $this->tpl_data['order_form_total_yesterday'] = OrderForm::whereDate('created_at', date('Y-m-d', strtotime("-1 days")))->where('status', '購物完成')->sum('real_total');

        // 本月訂單累計總金額
        $this->tpl_data['order_form_total_month'] = OrderForm::whereDate('created_at', '>=', date('Y-m-01'))
            ->whereDate('created_at', '<=', date('Y-m-t'))
            ->where('status', '購物完成')->sum('real_total');
        
        // 上月訂單累計總金額
        $this->tpl_data['order_form_total_prev_month'] = OrderForm::whereDate('created_at', '>=', date('Y-m-01', strtotime("-1 month")))
            ->whereDate('created_at', '<=', date('Y-m-t', strtotime("-1 month")))
            ->where('status', '購物完成')->sum('real_total');

        // 會員消費金額 TOP5
        $this->tpl_data['member_order_total_top5'] = Member::orderBy('order_form_total', 'desc')->limit(5)->get();

        // 本月熱門商品銷售數量 TOP5
        $this->tpl_data['order_item_month_top5'] = OrderItem::whereDate('created_at', '>=', date('Y-m-01'))
            ->whereDate('created_at', '<=', date('Y-m-t'))
            ->groupBy('product_id')
            ->select('product_id', DB::raw('count(*) as total'))
            ->orderBy('total', 'desc')
            ->with('product')
            ->limit(5)
            ->get();

        // 上個月熱門商品銷售數量 TOP5
        $this->tpl_data['order_item_last_month_top5'] = OrderItem::whereDate('created_at', '>=', date('Y-m-01', strtotime("-1 month")))
            ->whereDate('created_at', '<=', date('Y-m-t', strtotime("-1 month")))
            ->groupBy('product_id')
            ->select('product_id', DB::raw('count(*) as total'))
            ->orderBy('total', 'desc')
            ->with('product')
            ->limit(5)
            ->get();

        // 本月熱門商品銷售金額 TOP5
        $this->tpl_data['order_item_month_price_top5'] = OrderItem::whereDate('created_at', '>=', date('Y-m-01'))
            ->whereDate('created_at', '<=', date('Y-m-t'))
            ->groupBy('product_id')
            ->selectRaw('sum(sub_total) as sum, product_id')
            ->orderBy('sum', 'desc')
            ->with('product')
            ->limit(5)
            ->get();

        // 本月熱門商品銷售金額 TOP5
        $this->tpl_data['order_item_last_month_price_top5'] = OrderItem::whereDate('created_at', '>=', date('Y-m-01', strtotime("-1 month")))
            ->whereDate('created_at', '<=', date('Y-m-t', strtotime("-1 month")))
            ->groupBy('product_id')
            ->selectRaw('sum(sub_total) as sum, product_id')
            ->orderBy('sum', 'desc')
            ->with('product')
            ->limit(5)
            ->get();
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
        // ln -sr /home/t4zwwng5q1en/public_html/skjhs.onepoint.com.tw/storage/app/public /home/t4zwwng5q1en/public_html/skjhs.onepoint.com.tw/public/storage

        // 正常的目錄配置可使用此方法
        // Artisan::call('storage:link');
    }
}
