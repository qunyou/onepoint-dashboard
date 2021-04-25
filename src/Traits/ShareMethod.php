<?php

namespace Onepoint\Dashboard\Traits;

use Onepoint\Dashboard\Services\BaseService;

trait ShareMethod
{
    public $tpl = [];
    protected $base_service;

    public function share()
    {
        $base_service = new BaseService;
        $this->base_service = $base_service;
        $this->tpl_data['base_service'] = $this->base_service;
        
        // 設定語言
        // 如果有問題，執行清除動作
        // cache()->flush();
        // cache()->forget('backend_language');
        if (request('lang', false)) {
            cache()->forever('backend_language', request('lang'));
        }
        \App::setLocale(cache('backend_language', 'zh-tw'));
        
        // 判斷是否使用分站網址
        $this->backend_url_suffix = '';
        if (config('backend_url_suffix', false)) {
            $this->backend_url_suffix = config('backend_url_suffix') . '/';
        }
        config(['dashboard.uri' => $this->backend_url_suffix . config('dashboard.uri')]);

        // 檢視刪除資料狀態判斷
        $this->tpl_data['trashed'] = request('trashed', false);

        // // 檢視備份資料狀態判斷
        $this->tpl_data['version'] = request('version', false);

        // 排除分頁 qs
        $qs = $_GET;
        unset($qs['page']);
        $this->tpl_data['qs'] = $qs;
        $this->tpl_data['query_string'] = http_build_query($qs);

        // 當前分頁
        $this->tpl_data['page'] = request('page', 1);

        // 主導覽
        $this->tpl_data['navigation_item'] = config('backend.navigation_item');
    }

    // 列表基本設定資料
    public function listPrepare()
    {
        // $current_action = RouteService::getCurrentAction();

        // // 目前所在方法
        // $this->tpl_data['current_class_name'] = $current_action['class_name'];
        // if ($current_action['method'] == 'update' || $current_action['method'] == 'detail' || $current_action['method'] == 'duplicate') {
        //     $this->tpl_data['formPresenter'] = new FormPresenter;
        // }

        // 檢視刪除資料狀態判斷
        $component_datas['trashed'] = request('trashed', false);

        // 檢視備份資料狀態判斷
        $component_datas['version'] = request('version', false);

        // 後台右上下拉選單預設值
        $component_datas['dropdown_items'] = [];

        // 是否使用複製功能
        $component_datas['use_duplicate'] = false;

        // 是否使用版本功能
        $component_datas['use_version'] = false;

        // 是否使用排序功能
        $component_datas['use_sort'] = true;

        // 是否使用上下排序功能
        $component_datas['use_rearrange'] = false;

        // 是否使用拖曳排序
        $component_datas['use_drag_rearrange'] = false;

        // 隱藏檢視按鈕
        $component_datas['detail_hide'] = true;

        // 隱藏 footer 刪除選項
        $component_datas['footer_delete_hide'] = false;

        // 隱藏 footer 狀態選項
        $component_datas['footer_status_hide'] = false;

        // 隱藏 footer 下拉選單
        $component_datas['footer_dropdown_hide'] = false;

        // 隱藏 footer 排序功能
        $component_datas['footer_sort_hide'] = false;

        // 新增資料網址
        $component_datas['add_url'] = '';

        // 預覽網址
        $component_datas['preview_url'] = '';

        // 回列表網址
        // $component_datas['back_url'] = '';

        // 更新網址附加字串
        // $component_datas['update_url_append_string'] = $this->base_service->getQueryString(true, true);
        return $component_datas;
    }
}
