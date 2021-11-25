<?php

namespace Onepoint\Dashboard\Controllers;

use App\Http\Controllers\Controller;
use Onepoint\Dashboard\Presenters\FormPresenter;
use Onepoint\Dashboard\Presenters\PathPresenter;
use Onepoint\Dashboard\Repositories\SettingRepository;
use Onepoint\Dashboard\Services\ImageService;
use Onepoint\Dashboard\Services\StringService;
use Onepoint\Dashboard\Traits\ShareMethod;

/**
 * 網站設定
 */
class SettingController extends Controller
{
    use ShareMethod;

    /**
     * 只能手動開啟的功能
     * @var Boolean
     */
    var $is_root = true;

    /**
     * 建構子
     */
    public function __construct()
    {
        $this->share('setting_id', 'setting', 'pages.setting', 'dashboard::dashboard.');
    }

    /**
     * 一般管理者列表
     */
    public function model()
    {
        $component_datas = $this->listPrepare();

        // 表格欄位設定
        $component_datas['th'] = [
            ['title' => __('dashboard::setting.項目')],
            ['title' => __('dashboard::setting.設定值')],
        ];
        $component_datas['column'] = [
            ['type' => 'column', 'column_name' => 'title'],
            ['type' => 'function', 'function_name' => 'Onepoint\Dashboard\Controllers\SettingController@settingValueDisplay'],
        ];
        $this->tpl_data['model'] = request('model', 'global');
        $setting_repository = new SettingRepository;
        $component_datas['list'] = $setting_repository->getList($this->setting_id, config('backend.paginate'));
        $component_datas['update_uri'] = 'model-update';
        $component_datas['use_drag_rearrange'] = true;
        $component_datas['use_sort'] = false;
        $this->tpl_data['component_datas'] = $component_datas;
        return view($this->view_path . 'model', $this->tpl_data);
    }

    /**
     * 判斷checkbox是否勾選
     */
    public static function settingValueDisplay($element)
    {
        switch ($element->type) {
            case 'file_name':
                $str = ImageService::origin($element->setting_value, '', '', 'setting');
                break;

            case 'text':
            case 'editor':
                $str = StringService::htmlLimit($element->setting_value, 20, '...');
                break;

            default:
                $str = StringService::htmlLimit($element->setting_value, 20, '...');
                break;
        }
        return $str;
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
        $this->tpl_data['page_title'] = __('dashboard::backend.編輯');
        $model = request('model', 'global');
        $this->tpl_data['model'] = $model;
        // $this->tpl_data['formPresenter'] = new FormPresenter;
        if ($this->setting_id) {
            $setting_repository = new SettingRepository;
            $query = $setting_repository->getOne($this->setting_id);
            $this->tpl_data['setting'] = $query;

            // 刪除附檔
            $delete_file = request('delete_file', false);
            if ($delete_file) {
                ImageService::delete($query->setting_value);
                $setting_repository->model->find($this->setting_id)->update(['setting_value' => '']);
                $query = $setting_repository->getOne($this->setting_id);
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
        $setting_repository = new SettingRepository;
        $res = $setting_repository->setUpdate($this->setting_id);
        if ($res) {
            session()->flash('notify.message', __('dashboard::backend.資料編輯完成'));
            session()->flash('notify.type', 'success');
            return redirect($this->uri . 'model-update?model=' . $model . '&setting_id=' . $res);
        } else {
            session()->flash('notify.message', __('dashboard::backend.資料編輯失敗'));
            session()->flash('notify.type', 'danger');
            return redirect($this->uri . 'model-update?model=' . $model . '&setting_id=' . $this->setting_id)->withInput();
        }
    }

    /**
     * 一般管理者細節
     */
    public function modelDetail()
    {
        $permission_controller_string = get_class($this);
        $component_datas['permission_controller_string'] = $permission_controller_string;
        $model = request('model', false);
        $this->tpl_data['model'] = $model;
        $this->tpl_data['formPresenter'] = new FormPresenter;

        if ($this->setting_id) {
            $setting_repository = new SettingRepository;
            $setting = $setting_repository->getOne($this->setting_id);
            $this->tpl_data['setting'] = $setting;

            // 表單資料
            $this->tpl_data['form_array'] = [
                'description' => [
                    'input_type' => 'value',
                    'display_name' => __('dashboard::backend.說明'),
                ],
                'title' => [
                    'input_type' => 'value',
                    'display_name' => __('dashboard::backend.標題'),
                ],
                'setting_value' => [
                    'input_type' => 'value',
                    'display_name' => __('dashboard::setting.設定值'),
                ],
            ];

            // 樣版資料
            $component_datas['page_title'] = __('dashboard::backend.檢視');
            $component_datas['back_url'] = url($this->uri . 'model');
            $component_datas['dropdown_items']['btn_align'] = 'float-left';
            if (auth()->user()->hasAccess(['update-' . $permission_controller_string])) {
                $component_datas['dropdown_items']['items']['編輯'] = ['url' => url($this->uri . 'update?setting_id=' . $setting->id)];
                if (auth()->user()->hasAccess(['delete-' . $permission_controller_string])) {
                    $component_datas['dropdown_items']['items']['刪除'] = ['url' => url($this->uri . 'delete?setting_id=' . $setting->id)];
                }
            }
            // if (auth()->user()->hasAccess(['create-' . $permission_controller_string])) {
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
        $component_datas = $this->listPrepare();

        // 表格欄位設定
        $component_datas['th'] = [
            ['title' => __('dashboard::setting.項目')],
            ['title' => __('dashboard::setting.設定值')],
        ];
        $component_datas['column'] = [
            ['type' => 'column', 'column_name' => 'title'],
            ['type' => 'function', 'function_name' => 'Onepoint\Dashboard\Controllers\SettingController@settingValueDisplay'],
        ];
        $this->tpl_data['model'] = request('model', 'global');

        $setting_repository = new SettingRepository;
        // $component_datas['list'] = $article_repository->getList($this->article_id, config('backend.paginate'));
        $component_datas['list'] = $setting_repository->getList($this->setting_id, config('backend.paginate'));
        // config('backend.paginate')

        $component_datas['use_sort'] = false;
        $component_datas['footer_dropdown_hide'] = true;
        $this->tpl_data['component_datas'] = $component_datas;
        // dd($this->view_path . 'index');
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
            $setting_repository = new SettingRepository;
            $query = $setting_repository->getOne($this->setting_id);
            $this->tpl_data['setting'] = $query;

            // 刪除附檔
            $delete_file = request('delete_file', false);
            if ($delete_file) {
                ImageService::delete($query->setting_value);
                $setting_repository->model->find($this->setting_id)->update(['setting_value' => '']);
                $query = $etting_repository->getOne($this->setting_id);
            }
        }
        return view($this->view_path . 'update', $this->tpl_data);
    }

    /**
     * 送出編輯資料
     */
    public function putUpdate()
    {
        $this->IsRoot();
        $setting_repository = new SettingRepository;
        $res = $setting_repository->setUpdate($this->setting_id);
        if ($res) {
            session()->flash('notify.message', __('dashboard::backend.資料編輯完成'));
            session()->flash('notify.type', 'success');
            return redirect($this->uri . 'detail?setting_id=' . $res);
        } else {
            session()->flash('notify.message', __('dashboard::backend.資料編輯失敗'));
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

            $setting_repository = new SettingRepository;
            $setting = $setting_repository->getOne($this->setting_id);
            $this->tpl_data['setting'] = $setting;
            $component_datas = $this->detailPrepare();

            // 表單資料
            $this->tpl_data['form_array'] = [
                'model' => [
                    'input_type' => 'value',
                    'display_name' => '給哪個功能的設定',
                ],
                'type' => [
                    'input_type' => 'value',
                    'display_name' => '設定類別',
                ],
                'title' => [
                    'input_type' => 'value',
                    'display_name' => '標題',
                ],
                'description' => [
                    'input_type' => 'value',
                    'display_name' => '說明',
                ],
                'setting_key' => [
                    'input_type' => 'value',
                    'display_name' => '設定索引',
                ],
                'setting_value' => [
                    'input_type' => 'value',
                    'display_name' => '設定值',
                ],
                'sort' => [
                    'input_type' => 'value',
                    'display_name' => __('dashboard::backend.排序'),
                ],
                'status' => [
                    'input_type' => 'value',
                    'display_name' => __('dashboard::backend.狀態'),
                ],
                'note' => [
                    'input_type' => 'value',
                    'display_name' => __('dashboard::backend.備註'),
                ],
            ];
            $this->tpl_data['component_datas'] = $component_datas;
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
            $setting_repository = new SettingRepository;
            $setting_repository->delete($this->setting_id);
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
        $setting_repository = new SettingRepository;
        $result = $setting_repository->batch($settings);
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

    /**
     * 修改排序
     */
    public function rearrange()
    {
        if ($this->setting_id) {
            $setting_repository = new SettingRepository;
            $setting_repository->rearrange();
        }
        return redirect($this->uri . 'index?' . $this->base_service->getQueryString(true, true));
    }

    /**
     * 拖曳排序
     */
    public function dragSort()
    {
        $setting_repository = new SettingRepository;
        return $setting_repository->dragRearrange();
    }
}
