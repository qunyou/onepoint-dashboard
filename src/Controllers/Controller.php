<?php

namespace Onepoint\Dashboard\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Onepoint\Dashboard\Services\BaseService;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * 建構子
     */
    public function __construct($_uri)
    {
        // dd(__('dashbaord::backend.啟用'));
        // config(['dashboard.status_item' => ['啟用' => __('dashbaord::backend.啟用'), '停用' => __('dashbaord::backend.停用')]]);

        $this->base_services = new BaseService;
        $this->tpl_data = $this->base_services->tpl_data;
        $this->tpl_data['base_services'] = $this->base_services;
        $this->permission_controller_string = get_class($this);
        $this->tpl_data['component_datas']['permission_controller_string'] = $this->permission_controller_string;
        $this->tpl_data['navigation_item'] = config('backend.navigation_item');

        // 預設網址
        $this->uri = config('dashboard.uri') . '/' . $_uri . '/';
        $this->tpl_data['uri'] = $this->uri;
        $this->tpl_data['component_datas']['uri'] = $this->uri;

        // view 路徑
        $this->view_path = 'base::' . config('dashboard.view_path') . '.pages.' . $_uri . '.';
    }

    /**
     * 列表
     */
    public function index()
    {
        // 是否使用複製功能
        $this->tpl_data['component_datas']['use_duplicate'] = true;

        // 是否使用版本功能
        $this->tpl_data['component_datas']['use_version'] = true;

        // 是否使用排序功能
        $this->tpl_data['component_datas']['use_sort'] = true;

        // 是否使用上下排序功能
        $this->tpl_data['component_datas']['use_rearrange'] = true;

        // 權限設定
        $this->tpl_data['component_datas']['footer_delete_hide'] = false;
        if (auth()->user()->hasAccess(['create-' . $this->permission_controller_string])) {
            $this->tpl_data['component_datas']['add_url'] = url($this->uri . 'update');
        }
        if (auth()->user()->hasAccess(['update-' . $this->permission_controller_string])) {
            $this->tpl_data['component_datas']['footer_dropdown_hide'] = false;
            $this->tpl_data['component_datas']['footer_sort_hide'] = false;
            if (!auth()->user()->hasAccess(['delete-' . $this->permission_controller_string])) {
                $this->tpl_data['component_datas']['footer_delete_hide'] = true;
            }
        } else {
            $this->tpl_data['component_datas']['footer_dropdown_hide'] = true;
            $this->tpl_data['component_datas']['footer_sort_hide'] = true;
        }
        // if (auth()->user()->hasAccess(['create-' . $this->permission_controller_string])) {
        //     $this->tpl_data['component_datas']['dropdown_items']['items']['匯入'] = ['url' => url($this->uri . 'import')];
        // }
        if (auth()->user()->hasAccess(['update-' . $this->permission_controller_string])) {
            $this->tpl_data['component_datas']['dropdown_items']['items']['舊版本'] = ['url' => url($this->uri . 'index?version=true')];
        }
        if (auth()->user()->hasAccess(['delete-' . $this->permission_controller_string])) {
            $this->tpl_data['component_datas']['dropdown_items']['items']['資源回收'] = ['url' => url($this->uri . 'index?trashed=true')];
        }

        // 是否使用版本查詢
        // $this->slider_repository->use_version = true;
        // 列表資料查詢
        // $this->tpl_data['component_datas']['list'] = $this->slider_repository->getList($this->slider_id, config('backend.paginate'));
        $this->tpl_data['component_datas']['qs'] = $this->base_services->getQueryString();

        // 預覽按鈕網址
        // $this->tpl_data['component_datas']['preview_url'] = ['url' => url(config('backend.book.preview_url')) . '/', 'column' => 'slider_name_slug'];
        // return view($this->view_path . 'index', $this->tpl_data);
    }

    /**
     * 批次處理
     */
    public function batch($settings)
    {
        // $result = $this->slider_repository->batch($settings);
        // switch ($result['batch_method']) {
        switch ($settings['result']['batch_method']) {
            case 'restore':
            case 'force_delete':
                $back_url_str = 'index?trashed=true';
                break;
            default:
                $back_url_str = 'index';
                break;
        }
        return redirect($this->uri . $back_url_str);
    }

    /**
     * 設定細節樣版
     */
    public function detailTplConfig($id_string, $id_value)
    {
        // 樣版資料
        $component_datas['page_title'] = __('dashboard::backend.檢視');
        $component_datas['back_url'] = url($this->uri . 'index');
        $component_datas['dropdown_items']['btn_align'] = 'float-left';
        if (auth()->user()->hasAccess(['update-' . $this->permission_controller_string])) {
            $component_datas['dropdown_items']['items']['編輯'] = ['url' => url($this->uri . 'update?' . $id_string . '=' . $id_value)];
            if (auth()->user()->hasAccess(['delete-' . $this->permission_controller_string])) {
                $component_datas['dropdown_items']['items']['刪除'] = ['url' => url($this->uri . 'delete?' . $id_string . '=' . $id_value)];
            }
        }
        if (auth()->user()->hasAccess(['create-' . $this->permission_controller_string])) {
            $component_datas['dropdown_items']['items']['複製'] = ['url' => url($this->uri . 'duplicate?' . $id_string . '=' . $id_value)];
        }
        // if (config('backend.slider.preview_url', false)) {
        //     $component_datas['dropdown_items']['items']['預覽'] = ['url' => url(config('backend.slider.preview_url') . '/' . $id_value)];
        // }
        $this->tpl_data['component_datas'] = $component_datas;
    }
}
