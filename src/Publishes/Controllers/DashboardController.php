<?php

namespace App\Http\Controllers;

use Artisan;
use Onepoint\Dashboard\Services\BaseService;
use Onepoint\Dashboard\Presenters\PathPresenter;

/**
 * 登入預設頁
 */
class DashboardController extends Controller
{
    /**
     * 建構子
     */
    public function __construct(BaseService $base_services)
    {
        $this->base_services = $base_services;
        $this->tpl_data = $base_services->tpl_data;
        $this->tpl_data['base_services'] = $this->base_services;

        // 預設網址
        $this->uri = config('dashboard.uri') . 'dashboard/';
        $this->tpl_data['uri'] = $this->uri;

        // view 路徑
        // $this->view_path = config('backend.view_path') . '.pages.dashboard.';

        // 主功能標題
        $this->tpl_data['page_header'] = __('backend.預設首頁');
    }

    /**
     * 首頁
     */
    public function index(PathPresenter $path_presenter)
    {
        $this->tpl_data['path_presenter'] = $path_presenter;
        $this->tpl_data['page_title'] = trans('backend.預設首頁');
        return view($path_presenter->backend_view('pages.dashboard.index'), $this->tpl_data);
    }

    /**
     * 建立軟連結(symbolic link)
     */
    function storageLink()
    {
        Artisan::call('storage:link');
    }
}