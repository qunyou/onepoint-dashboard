<?php
namespace App\Http\Controllers;

use Cache;
use Onepoint\Dashboard\Services\BaseService;
use Onepoint\Dashboard\Services\FileService;
use App\Repositories\ResourceRepository;
use App\Repositories\ResourceCategoryRepository;

/**
 * 資源
 */
class ResourceController extends Controller
{
    /**
     * 建構子
     */
    function __construct(BaseService $base_services, ResourceRepository $resource_repository, ResourceCategoryRepository $resource_category_repository)
    {
        $this->base_services = $base_services;
        $this->tpl_data = $base_services->tpl_data;
        $this->tpl_data['base_services'] = $this->base_services;
        $this->resource_repository = $resource_repository;
        $this->resource_category_repository = $resource_category_repository;
        $this->permission_controller_string = class_basename(get_class($this));
        $this->tpl_data['permission_controller_string'] = $this->permission_controller_string;

        // 預設網址
        $this->uri = config('dashboard.uri') . '/resource/';
        $this->tpl_data['uri'] = $this->uri;

        // view 路徑
        $this->view_path = config('dashboard.view_path') . '.pages.resource.';

        // 主功能標題
        $this->tpl_data['page_header'] = __('resource.資源管理');
        $this->resource_id = request('resource_id', false);
        $this->tpl_data['resource_id'] = $this->resource_id;
    }

    /**
     * 列表
     */
    public function index()
    {
        $this->tpl_data['list'] = $this->resource_repository->getList($this->resource_id, config('backend.paginate'));

        // 樣版資料
        if (!$this->tpl_data['trashed']) {
            $component_datas['page_title'] = __('backend.列表');
        } else {
            $component_datas['page_title'] = __('backend.資源回收');
        }
        $component_datas['back_url'] = url($this->uri . 'index');
        $this->tpl_data['footer_delete_hide'] = false;
        if (auth()->user()->hasAccess(['create-' . $this->permission_controller_string])) {
            $component_datas['add_url'] = url($this->uri . 'update');
        }
        if (auth()->user()->hasAccess(['update-' . $this->permission_controller_string])) {
            $this->tpl_data['footer_dropdown_hide'] = false;
            $this->tpl_data['footer_sort_hide'] = false;
            if (!auth()->user()->hasAccess(['delete-' . $this->permission_controller_string])) {
                $this->tpl_data['footer_delete_hide'] = true;
            }
        } else {
            $this->tpl_data['footer_dropdown_hide'] = true;
            $this->tpl_data['footer_sort_hide'] = true;
        }
        $this->tpl_data['use_version'] = true;
        $this->tpl_data['use_duplicate'] = true;
        $component_datas['trashed'] = $this->tpl_data['trashed'];
        $component_datas['version'] = $this->tpl_data['version'];
        $component_datas['qs'] = $this->tpl_data['qs'];
        $component_datas['dropdown_items'] = [];
        // if (auth()->user()->hasAccess(['create-' . $this->permission_controller_string])) {
        //     $component_datas['dropdown_items']['items']['匯入'] = ['url' => url($this->uri . 'index?trashed=true')];
        // }
        if (auth()->user()->hasAccess(['update-' . $this->permission_controller_string])) {
            $component_datas['dropdown_items']['items']['舊版本'] = ['url' => url($this->uri . 'index?version=true')];
        }
        if (auth()->user()->hasAccess(['delete-' . $this->permission_controller_string])) {
            $component_datas['dropdown_items']['items']['資源回收'] = ['url' => url($this->uri . 'index?trashed=true')];
        }
        $this->tpl_data['component_datas'] = $component_datas;
        return view($this->view_path . 'index', $this->tpl_data);
    }

    /**
     * 批次處理
     */
    public function putIndex()
    {
        return $this->batch();
    }

    /**
     * 批次處理
     */
    public function batch()
    {
        $settings['file_field'] = 'file_name';
        $settings['folder'] = 'resource';
        $settings['image_scale'] = true;
        $settings['use_version'] = true;
        $result = $this->resource_repository->batch($settings);
        switch ($result['batch_method']) {
            case 'restore':
            case 'force_delete':
                $back_url_str = 'index?trashed=true';
                break;
            default :
                $back_url_str = 'index';
                break;
        }
        return redirect($this->uri . $back_url_str);
    }

    /**
     * 編輯
     */
    public function update()
    {
        $this->tpl_data['resource'] = false;
        
        // 分類值陣列
        $category_id_array = [];
        if ($this->resource_id) {
            $page_title = __('backend.編輯');
            $query = $this->resource_repository->getOne($this->resource_id);

            // 複製時不顯示圖片
            if (isset($this->tpl_data['duplicate']) && $this->tpl_data['duplicate']) {
                $query->file_name = '';
                $page_title = __('backend.複製');
            }
            $this->tpl_data['resource'] = $query;
            foreach ($query->resource_category as $value) {
                $category_id_array[] = $value->id;
            }
        } else {
            $page_title = __('backend.新增');
        }

        // 分類選單資料
        $category_select_item = $this->resource_category_repository->getOptionItem();

        // 一般表單資料
        $this->tpl_data['form_array_normal'] = [
            'resource_title' => [
                'input_type' => 'text',
                'display_name' => __('backend.標題'),
            ],
            'resource_category_id[]' => [
                'input_type' => 'select',
                'display_name' => __('backend.分類'),
                'input_value' => $category_id_array,
                'option' => $category_select_item,
                'attribute' => ['multiple' => 'multiple', 'size' => 5],
                'help' => '按著Ctrl點選，可複選多個項目',
            ],
            'resource_url' => [
                'input_type' => 'text',
                'display_name' => __('backend.連結網址'),
            ],
            'resource_target' => [
                'input_type' => 'select',
                'display_name' => __('backend.連結開啟方法'),
                'option' => config('backend.url_target_item'),
            ],
            'sort' => [
                'input_type' => 'number',
                'display_name' => __('backend.排序'),
            ],
            'status' => [
                'input_type' => 'select',
                'display_name' => __('backend.狀態'),
                'option' => config('backend.status_item'),
            ],
            'note' => [
                'input_type' => 'textarea',
                'display_name' => __('backend.備註'),
                'rows' => 5,
            ],
        ];

        // 樣版資料
        $component_datas['page_title'] = $page_title;
        $component_datas['back_url'] = url($this->uri . 'index');
        $this->tpl_data['component_datas'] = $component_datas;
        return view($this->view_path . 'update', $this->tpl_data);
    }

    /**
     * 送出編輯資料
     */
    public function putUpdate()
    {
        $res = $this->resource_repository->setUpdate($this->resource_id);
        if ($res) {
            session()->flash('notify.message', __('backend.資料編輯完成'));
            session()->flash('notify.type', 'success');
            return redirect($this->uri . 'detail?resource_id=' . $res);
        } else {
            session()->flash('notify.message', __('backend.資料編輯失敗'));
            session()->flash('notify.type', 'danger');
            return redirect($this->uri . 'update?resource_id=' . $this->resource_id)->withInput();
        }
    }

    /**
     * 複製
     */
    public function duplicate()
    {
        $this->tpl_data['duplicate'] = true;
        return $this->update();
    }

    /**
     * 複製
     */
    public function putDuplicate()
    {
        $this->resource_id = 0;
        return $this->putUpdate();
    }

    /**
     * 細節
     */
    public function detail()
    {
        if ($this->resource_id) {
            $resource = $this->resource_repository->getOne($this->resource_id);
            $this->tpl_data['resource'] = $resource;
            $category_value_str = $resource->resource_category->implode('category_name', ', ');

            // 表單資料
            $this->tpl_data['form_array'] = [
                'resource_title' => [
                    'input_type' => 'value',
                    'display_name' => __('backend.標題'),
                ],
                'resource_category_id' => [
                    'input_type' => 'value',
                    'display_name' => __('backend.分類'),
                    'input_value' => $category_value_str
                ],
                'resource_url' => [
                    'input_type' => 'value',
                    'display_name' => __('backend.連結網址'),
                ],
                'resource_target' => [
                    'input_type' => 'value',
                    'display_name' => __('backend.連結開啟方法'),
                ],
                'click' => [
                    'input_type' => 'value',
                    'display_name' => __('backend.點擊'),
                ],
                'sort' => [
                    'input_type' => 'value',
                    'display_name' => __('backend.排序'),
                ],
                'status' => [
                    'input_type' => 'value',
                    'display_name' => __('backend.狀態'),
                ],
                'note' => [
                    'input_type' => 'value',
                    'display_name' => __('backend.備註'),
                ],
            ];

            // 樣版資料
            $component_datas['page_title'] = __('backend.檢視');
            $component_datas['back_url'] = url($this->uri . 'index');
            $component_datas['dropdown_items']['btn_align'] = 'float-left';
            if (auth()->user()->hasAccess(['update-' . $this->permission_controller_string])) {
                $component_datas['dropdown_items']['items']['編輯'] = ['url' => url($this->uri . 'update?resource_id=' . $resource->id)];
                if (auth()->user()->hasAccess(['delete-' . $this->permission_controller_string])) {
                    $component_datas['dropdown_items']['items']['刪除'] = ['url' => url($this->uri . 'delete?resource_id=' . $resource->id)];
                }
            }
            if (auth()->user()->hasAccess(['create-' . $this->permission_controller_string])) {
                $component_datas['dropdown_items']['items']['複製'] = ['url' => url($this->uri . 'duplicate?resource_id=' . $resource->id)];
            }
            $component_datas['dropdown_items']['items']['預覽'] = ['url' => url(config('backend.resource.preview_url', 'detail/') . $resource->id)];
            $this->tpl_data['component_datas'] = $component_datas;
            return view($this->view_path . 'detail', $this->tpl_data);
        } else {
            return redirect($this->uri . 'index');
        }
    }

    /**
     * 版本還原
     */
    public function applyVersion()
    {
        if ($this->resource_id) {
            $version_id = request('version_id', 0);
            if ($version_id) {
                $this->resource_category_repository->applyVersion($this->resource_id, $version_id);
            }
        }
        return redirect($this->uri . 'detail?resource_id=' . $this->role_id);
    }

    /**
     * 刪除
     */
    public function delete()
    {
        if ($this->resource_id) {
            $this->resource_repository->delete($this->resource_id);
        }
        return redirect($this->uri . 'index');
    }
}
