<?php

namespace App\Http\Controllers;

use Onepoint\Dashboard\Presenters\PathPresenter;
use Onepoint\Dashboard\Services\BaseService;
use Onepoint\Dashboard\Services\ImageService;
use App\Repositories\SettingRepository;

/**
 * 網站設定
 */
class SettingController extends Controller
{
    /**
     * 建構子
     */
    public function __construct(BaseService $base_services, ImageService $image_services, SettingRepository $setting_repository, PathPresenter $path_presenter)
    {
        $this->base_services = $base_services;
        $this->tpl_data = $base_services->tpl_data;
        $this->tpl_data['base_services'] = $this->base_services;
        $this->tpl_data['path_presenter'] = $path_presenter;
        $this->permission_controller_string = get_class($this);
        $this->tpl_data['permission_controller_string'] = $this->permission_controller_string;
        $this->tpl_data['navigation_item'] = config('backend.navigation_item');

        $this->setting_repository = $setting_repository;

        // 預設網址
        $this->uri = config('dashboard.uri') . '/setting/';
        $this->tpl_data['uri'] = $this->uri;

        // view 路徑
        $this->view_path = config('dashboard.view_path') . '.pages.setting.';

        $this->setting_id = request('setting_id', false);
        $this->tpl_data['setting_id'] = $this->setting_id;

        // 只能手動開啟的功能
        $this->is_root = true;
    }

    /**
     * 一般管理者列表
     */
    public function model()
    {
        $this->tpl_data['model'] = request('model', 'global');
        $this->tpl_data['list'] = $this->setting_repository->getList(false, $this->tpl_data['model']);
        if (isset(config('site.setting.model')[$this->tpl_data['model']])) {
            $this->tpl_data['page_title'] = config('site.setting.model')[$this->tpl_data['model']] . __('backend.列表');
        } else {
            $this->tpl_data['page_title'] = __('backend.列表');
        }
        return view($this->view_path . 'model', $this->tpl_data);
    }

    /**
     * 批次處理
     */
    public function putModel()
    {
        $model = request('model', false);
        if ($model) {
            $query_string = 'model=' . $model;
        } else {
            $query_string = '';
        }
        return $this->batch('model', $query_string);
    }

    /**
     * 一般管理者編輯
     */
    public function modelUpdate()
    {
        $this->tpl_data['page_title'] = __('backend.編輯');
        $model = request('model', false);
        $this->tpl_data['model'] = $model;
        if ($this->setting_id && $model) {
            $query = $this->setting_repository->getOne($this->setting_id);
            $this->tpl_data['setting'] = $query;

            // 刪除附檔
            $delete_file = request('delete_file', false);
            if ($delete_file) {
                ImageService::delete($query->setting_value);
                $this->setting_repository->model->find($this->setting_id)->update(['setting_value' => '']);
                $query = $this->setting_repository->getOne($this->setting_id);
            }

            //　logo 圖片說明
            $this->tpl_data['file_input_help'] = '';
            // if ($query->setting_key == 'top_logo' || $query->setting_key == 'footer_logo') {
                // $this->tpl_data['file_input_help'] = '可上傳 jpg、png 圖檔，72dpi，RGB 色彩模式，建議圖片尺寸200x120，png透明去背景圖片';
            // }
            return view($this->view_path . 'model-update', $this->tpl_data);
        }
        return redirect($this->uri . 'model?model=' . $model);
    }

    /**
     * 一般管理者編輯資料
     */
    public function putModelUpdate()
    {
        $model = request('model', false);
        $res = $this->setting_repository->setUpdate($this->setting_id);
        if ($res) {
            session()->flash('notify.message', __('backend.資料編輯完成'));
            session()->flash('notify.type', 'success');
            return redirect($this->uri . 'model-detail?model=' . $model . '&setting_id=' . $res);
        } else {
            session()->flash('notify.message', __('backend.資料編輯失敗'));
            session()->flash('notify.type', 'danger');
            return redirect($this->uri . 'model-update?model=' . $model . '&setting_id=' . $this->setting_id)->withInput();
        }
    }

    /**
     * 一般管理者細節
     */
    public function modelDetail()
    {
        $model = request('model', false);
        $this->tpl_data['model'] = $model;

        if ($this->setting_id) {
            $setting = $this->setting_repository->getOne($this->setting_id);
            $this->tpl_data['setting'] = $setting;

            // 表單資料
            $this->tpl_data['form_array'] = [
                'description' => [
                    'input_type' => 'value',
                    'display_name' => __('backend.說明'),
                ],
                'title' => [
                    'input_type' => 'value',
                    'display_name' => __('backend.標題'),
                ],
                'setting_value' => [
                    'input_type' => 'value',
                    'display_name' => __('setting.設定值'),
                ],
            ];

            // 樣版資料
            $component_datas['page_title'] = __('backend.檢視');
            $component_datas['back_url'] = url($this->uri . 'model');
            $component_datas['dropdown_items']['btn_align'] = 'float-left';
            if (auth()->user()->hasAccess(['update-' . $this->permission_controller_string])) {
                $component_datas['dropdown_items']['items']['編輯'] = ['url' => url($this->uri . 'update?setting_id=' . $setting->id)];
                if (auth()->user()->hasAccess(['delete-' . $this->permission_controller_string])) {
                    $component_datas['dropdown_items']['items']['刪除'] = ['url' => url($this->uri . 'delete?setting_id=' . $setting->id)];
                }
            }
            // if (auth()->user()->hasAccess(['create-' . $this->permission_controller_string])) {
            //     $component_datas['dropdown_items']['items']['複製'] = ['url' => url($this->uri . 'duplicate?setting_id=' . $setting->id)];
            // }
            $this->tpl_data['component_datas'] = $component_datas;
            return view($this->view_path . 'model-detail', $this->tpl_data);
        } else {
            return redirect($this->uri . 'index');
        }
    }

    /**
     * 列表
     */
    public function index()
    {
        $this->IsRoot();
        if (!$this->tpl_data['trashed']) {
            $this->tpl_data['page_title'] = '網站設定列表';
        } else {
            $this->tpl_data['page_title'] = '網站設定列表-資源回收';
        }
        $this->tpl_data['model'] = request('model', '');
        $this->tpl_data['list'] = $this->setting_repository->getList($this->tpl_data['trashed'], $this->tpl_data['model']);
        return view($this->view_path . 'index', $this->tpl_data);
    }

    /**
     * 批次處理
     */
    public function putIndex()
    {
        $this->IsRoot();
        return $this->batch();
    }

    /**
     * 編輯
     */
    public function update()
    {
        $this->IsRoot();
        $this->tpl_data['setting'] = false;
        if ($this->setting_id) {
            $this->tpl_data['page_title'] = __('backend.編輯');
            $query = $this->setting_repository->getOne($this->setting_id);
            $this->tpl_data['setting'] = $query;

            // 刪除附檔
            $delete_file = request('delete_file', false);
            if ($delete_file) {
                ImageService::delete($query->setting_value);
                $this->setting_repository->model->find($this->setting_id)->update(['setting_value' => '']);
                $query = $this->setting_repository->getOne($this->setting_id);
            }
        } else {
            $this->tpl_data['page_title'] = __('backend.新增');
        }
        return view($this->view_path . 'update', $this->tpl_data);
    }

    /**
     * 送出編輯資料
     */
    public function putUpdate()
    {
        $this->IsRoot();
        $res = $this->setting_repository->setUpdate($this->setting_id);
        if ($res) {
            session()->flash('notify.message', __('backend.資料編輯完成'));
            session()->flash('notify.type', 'success');
            return redirect($this->uri . 'detail?setting_id=' . $res);
        } else {
            session()->flash('notify.message', __('backend.資料編輯失敗'));
            session()->flash('notify.type', 'danger');
            return redirect($this->uri . 'update?setting_id=' . $this->setting_id)->withInput();
        }
    }

    /**
     * 細節
     */
    public function detail()
    {
        $this->IsRoot();
        if ($this->setting_id) {
            $this->tpl_data['page_title'] = '檢視網站設定資料';
            $this->tpl_data['setting'] = $this->setting_repository->getOne($this->setting_id);
            return view($this->view_path . 'detail', $this->tpl_data);
        } else {
            return redirect($this->uri . 'index');
        }
    }

    /**
     * 刪除
     */
    public function delete()
    {
        $this->IsRoot();
        if ($this->setting_id) {
            $this->setting_repository->delete($this->setting_id);
        }
        return redirect($this->uri . 'index');
    }

    /**
     * 只能手動開啟的功能
     */
    private function IsRoot()
    {
        if (!$this->is_root) {
            exit;
        }
    }

    /**
     * 批次處理
     */
    private function batch($method = 'index', $query_string = '')
    {
        $settings['file_field'] = 'file_name';
        $settings['folder'] = 'setting';
        $settings['image_scale'] = false;
        $settings['use_version'] = true;
        $result = $this->setting_repository->batch($settings);
        switch ($result['batch_method']) {
            case 'restore':
            case 'force_delete':
                if (!empty($query_string)) {
                    $query_string = '&' . $query_string;
                }
                $back_url_str = $method . '?trashed=true' . $query_string;
                break;
            default:
                if (!empty($query_string)) {
                    $query_string = '?' . $query_string;
                }
                $back_url_str = $method . $query_string;
                break;
        }
        return redirect($this->uri . $back_url_str);
    }
}
