<?php

namespace App\Http\Controllers;

use Cache;
use Onepoint\Dashboard\Services\BaseService;
use Onepoint\Dashboard\Services\ImageService;
use Onepoint\Dashboard\Services\FileService;
use App\Repositories\PageRepository;

/**
 * 單頁
 */
class PageController extends Controller
{
    /**
     * 建構子
     */
    function __construct(BaseService $base_services, PageRepository $page_repository)
    {
        $this->base_services = $base_services;
        $this->tpl_data = $base_services->tpl_data;
        $this->tpl_data['base_services'] = $this->base_services;
        $this->page_repository = $page_repository;
        $this->permission_controller_string = class_basename(get_class($this));
        $this->tpl_data['permission_controller_string'] = $this->permission_controller_string;

        // 預設網址
        $this->uri = config('dashboard.uri') . '/page/';
        $this->tpl_data['uri'] = $this->uri;

        // view 路徑
        $this->view_path = config('dashboard.view_path') . '.pages.page.';

        // 主功能標題
        $this->tpl_data['page_header'] = __('page.文章管理');
        $this->page_id = request('page_id', false);
        $this->tpl_data['page_id'] = $this->page_id;
    }

    /**
     * 列表
     */
    public function index(ImageService $image_service)
    {
        $this->tpl_data['image_service'] = $image_service;
        $this->tpl_data['list'] = $this->page_repository->getList($this->page_id, config('backend.paginate'));

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
        $settings['folder'] = 'page';
        $settings['image_scale'] = true;
        $settings['use_version'] = true;
        $result = $this->page_repository->batch($settings);
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
        $this->tpl_data['page'] = false;
        if ($this->page_id) {
            $page_title = __('backend.編輯');
            $query = $this->page_repository->getOne($this->page_id);

            // 複製
            if (isset($this->tpl_data['duplicate']) && $this->tpl_data['duplicate']) {
                $page_title = __('backend.複製');
            }
            $this->tpl_data['page'] = $query;
        } else {
            $page_title = __('backend.新增');
        }

        // 一般表單資料
        $this->tpl_data['form_array_normal'] = [
            'page_title' => [
                'input_type' => 'text',
                'display_name' => __('backend.標題'),
            ],
            'page_content' => [
                'input_type' => 'tinymce',
                'display_name' => __('backend.內容'),
            ],
        ];

        // 進階表單資料
        $this->tpl_data['form_array_advanced'] = [
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

        // 社群及SEO
        $this->tpl_data['form_array_seo'] = [
            'html_title' => [
                'input_type' => 'text',
                'display_name' => __('backend.網頁標題'),
            ],
            'meta_keywords' => [
                'input_type' => 'text',
                'display_name' => __('backend.關鍵字'),
            ],
            'meta_description' => [
                'input_type' => 'text',
                'display_name' => __('backend.網頁敘述'),
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
        $res = $this->page_repository->setUpdate($this->page_id);
        if ($res) {
            session()->flash('notify.message', __('backend.資料編輯完成'));
            session()->flash('notify.type', 'success');
            return redirect($this->uri . 'detail?page_id=' . $res);
        } else {
            session()->flash('notify.message', __('backend.資料編輯失敗'));
            session()->flash('notify.type', 'danger');
            return redirect($this->uri . 'update?page_id=' . $this->page_id)->withInput();
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
        $this->page_id = 0;
        return $this->putUpdate();
    }

    /**
     * 細節
     */
    public function detail()
    {
        if ($this->page_id) {
            $page = $this->page_repository->getOne($this->page_id);
            $this->tpl_data['page'] = $page;

            // 表單資料
            $this->tpl_data['form_array'] = [
                'page_title' => [
                    'input_type' => 'value',
                    'display_name' => __('backend.標題'),
                ],
                'page_content' => [
                    'input_type' => 'value',
                    'display_name' => __('backend.內容'),
                ],
                'html_title' => [
                    'input_type' => 'value',
                    'display_name' => __('backend.網頁標題'),
                ],
                'meta_keywords' => [
                    'input_type' => 'value',
                    'display_name' => __('backend.關鍵字'),
                ],
                'meta_description' => [
                    'input_type' => 'value',
                    'display_name' => __('backend.網頁敘述'),
                ],
                'click' => [
                    'input_type' => 'value',
                    'display_name' => __('backend.點擊數'),
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
                $component_datas['dropdown_items']['items']['編輯'] = ['url' => url($this->uri . 'update?page_id=' . $page->id)];
                if (auth()->user()->hasAccess(['delete-' . $this->permission_controller_string])) {
                    $component_datas['dropdown_items']['items']['刪除'] = ['url' => url($this->uri . 'delete?page_id=' . $page->id)];
                }
            }
            if (auth()->user()->hasAccess(['create-' . $this->permission_controller_string])) {
                $component_datas['dropdown_items']['items']['複製'] = ['url' => url($this->uri . 'duplicate?page_id=' . $page->id)];
            }
            $component_datas['dropdown_items']['items']['預覽'] = ['url' => url(config('backend.page.preview_url', 'detail/') . $page->id)];
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
        if ($this->page_id) {
            $version_id = request('version_id', 0);
            if ($version_id) {
                $this->page_repository->applyVersion($this->page_id, $version_id);
            }
        }
        return redirect($this->uri . 'detail?page_id=' . $this->role_id);
    }

    /**
     * 刪除
     */
    public function delete()
    {
        if ($this->page_id) {
            $this->page_repository->delete($this->page_id);
        }
        return redirect($this->uri . 'index');
    }
}
