<?php

namespace Onepoint\Dashboard\Traits;

use Onepoint\Dashboard\Services\BaseService;

trait ShareMethod
{
    public $tpl = [];
    protected $base_service;

    /**
     * 基本設定資料
     * 
     * @param $id_string            int 主資料 id 字串
     * @param $uri                  str 預設網址
     * @param $view_path            str view 路徑
     * @param $view_path_prefix     str view 路徑前綴，無值時預設前綴，有值時自訂前綴
     */
    public function share($id_string = '', $uri = '', $view_path = '', $view_path_prefix = '')
    {
        $base_service = new BaseService;
        $this->base_service = $base_service;
        $this->tpl_data['base_service'] = $this->base_service;

        // 主資料 id query string 字串
        $this->tpl_data['id_string'] = $id_string;
        if (!empty($id_string)) {
            $this->{$id_string} = request($id_string, 0);
        } else {
            $this->{$id_string} = 0;
        }
        $this->tpl_data[$id_string] = $this->{$id_string};

        // 預設網址
        if (!empty($uri)) {
            $this->uri = config('dashboard.uri') . '/' . $uri . '/';
            $this->tpl_data['uri'] = $this->uri;
        }

        // view 路徑
        if (!empty($view_path)) {
            if (empty($view_path_prefix)) {
                $this->view_path = 'base::' . config('dashboard.view_path') . '.' . $view_path . '.';
            } else {
                $this->view_path = $view_path_prefix . $view_path . '.';
            }
        }
        
        // 設定語言
        // 如果有問題，執行清除動作
        // cache()->flush();
        // cache()->forget('backend_language');
        if (request('lang', false)) {
            cache()->forever('backend_language', request('lang'));
        }
        if (array_key_exists(cache('backend_language', 'zh-tw'), config('backend.language', ['zh-tw' => '繁體中文']))) {
            \App::setLocale(cache('backend_language'));
        }
        
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
        // $qs = $_GET;
        // unset($qs['page']);
        // $this->tpl_data['qs'] = $qs;
        // $this->tpl_data['query_string'] = http_build_query($qs);

        // 當前分頁
        $this->tpl_data['page'] = request('page', 1);

        // 主導覽
        $this->tpl_data['navigation_item'] = config('backend.navigation_item');
    }

    /**
     * 列表基本設定資料
     */
    public function listPrepare()
    {
        $permission_controller_string = get_class($this);
        $component_datas['id_string'] = $this->tpl_data['id_string'];
        
        // 權限判斷字串
        if (!empty($permission_controller_string)) {
            $component_datas['permission_controller_string'] = $permission_controller_string;
        }

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

        // 是否使用列表勾選功能
        $component_datas['use_check_box'] = true;

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
        // $component_datas['add_url'] = '';

        // 預覽網址
        $component_datas['preview_url'] = '';

        // 預設網址
        $component_datas['uri'] = $this->uri;

        // 回列表網址
        $component_datas['back_url'] = url($this->uri . 'index');

        // 權限設定
        // if (auth()->user()->hasAccess(['create-' . $permission_controller_string])) {
        //     $component_datas['add_url'] = url($this->uri . 'update');
        // }
        if (config('user.use_role')) {
            if (auth()->user()->hasAccess(['update-' . $permission_controller_string])) {
                if (!auth()->user()->hasAccess(['delete-' . $permission_controller_string])) {
                    $component_datas['footer_delete_hide'] = true;
                }
            } else {
                $component_datas['footer_dropdown_hide'] = true;
                $component_datas['footer_sort_hide'] = true;
            }
            if (auth()->user()->hasAccess(['update-' . $permission_controller_string])) {
                $component_datas['dropdown_items']['items']['版本'] = ['url' => url($this->uri . 'index?version=true')];
            }
            if (auth()->user()->hasAccess(['delete-' . $permission_controller_string])) {
                $component_datas['dropdown_items']['items']['資源回收'] = ['url' => url($this->uri . 'index?trashed=true')];
            }
        }

        // 更新網址附加字串
        // $component_datas['update_url_append_string'] = $this->base_service->getQueryString(true, true);
        return $component_datas;
    }

    /**
     * 細節基本設定資料
     */
    public function detailPrepare()
    {
        // 權限判斷字串
        $permission_controller_string = get_class($this);
        $component_datas['permission_controller_string'] = $permission_controller_string;

        // 回列表網址
        $component_datas['back_url'] = url($this->uri . 'index?' . $this->base_service->getQueryString(true, true));

        // 主資料 id query string 字串
        if (!empty($this->tpl_data['id_string'])) {
            if (auth()->user()->hasAccess(['update-' . $permission_controller_string])) {
                $component_datas['dropdown_items']['items']['編輯'] = ['url' => url($this->uri . 'update?' . $this->tpl_data['id_string'] . '=' . $this->tpl_data[$this->tpl_data['id_string']])];
            }
            if (auth()->user()->hasAccess(['create-' . $permission_controller_string])) {
                $component_datas['dropdown_items']['items']['複製'] = ['url' => url($this->uri . 'duplicate?' . $this->tpl_data['id_string'] . '=' . $this->tpl_data[$this->tpl_data['id_string']])];
            }
            if (auth()->user()->hasAccess(['delete-' . $permission_controller_string])) {
                $component_datas['dropdown_items']['items']['刪除'] = ['url' => url($this->uri . 'delete?' . $this->tpl_data['id_string'] . '=' . $this->tpl_data[$this->tpl_data['id_string']])];
            }
            // if (config('backend.article.preview_url', false)) {
            //     $component_datas['dropdown_items']['items']['預覽'] = ['url' => url(config('backend.article.preview_url', '') . $this->tpl_data[$id_string])];
            // }
        }
        return $component_datas;
    }

    /**
     * 批次處理
     */
    public function batch($repository, $settings)
    {
        // $settings['use_version'] = true;
        $result = $repository->batch($settings);
        switch ($result['batch_method']) {
            case 'restore':
            case 'force_delete':
                $back_url_str = 'index?' . $this->base_service->getQueryString(true, true) . '&trashed=true';
                break;
            default:
                $back_url_str = 'index?' . $this->base_service->getQueryString(true, true);
                break;
        }
        return redirect($this->uri . $back_url_str);
    }
}
