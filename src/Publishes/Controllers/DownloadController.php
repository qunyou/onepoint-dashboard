<?php
namespace App\Http\Controllers;

use Cache;
use Onepoint\Dashboard\Services\BaseService;
use Onepoint\Dashboard\Services\FileService;
use Onepoint\Dashboard\Services\ImageService;
use App\Repositories\DownloadRepository;
use App\Repositories\DownloadCategoryRepository;

/**
 * 下載
 */
class DownloadController extends Controller
{
    /**
     * 建構子
     */
    function __construct(BaseService $base_services, DownloadRepository $download_repository, DownloadCategoryRepository $download_category_repository)
    {
        $this->base_services = $base_services;
        $this->tpl_data = $base_services->tpl_data;
        $this->tpl_data['base_services'] = $this->base_services;
        $this->download_repository = $download_repository;
        $this->download_category_repository = $download_category_repository;
        $this->permission_controller_string = class_basename(get_class($this));
        $this->tpl_data['permission_controller_string'] = $this->permission_controller_string;

        // 預設網址
        $this->uri = config('dashboard.uri') . '/download/';
        $this->tpl_data['uri'] = $this->uri;

        // view 路徑
        $this->view_path = config('dashboard.view_path') . '.pages.download.';

        // 主功能標題
        $this->tpl_data['page_header'] = __('download.下載管理');
        $this->download_id = request('download_id', false);
        $this->tpl_data['download_id'] = $this->download_id;
    }

    /**
     * 列表
     */
    public function index()
    {
        $this->tpl_data['list'] = $this->download_repository->getList($this->download_id, config('backend.paginate'));

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
        $settings['folder'] = 'download';
        $settings['image_scale'] = true;
        $settings['use_version'] = true;
        $result = $this->download_repository->batch($settings);
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
        $this->tpl_data['download'] = false;
        
        // 分類值陣列
        $category_id_array = [];
        if ($this->download_id) {
            $page_title = __('backend.編輯');
            $query = $this->download_repository->getOne($this->download_id);

            // 複製時不顯示圖片
            if (isset($this->tpl_data['duplicate']) && $this->tpl_data['duplicate']) {
                $query->file_name = '';
                $page_title = __('backend.複製');
            }
            $this->tpl_data['download'] = $query;

            // 刪除附檔
            $delete_file = request('delete_file', false);
            if ($delete_file) {
                ImageService::delete($query->file_name, true, 'download');
                $query->file_name = '';
                $query->save();
            }
            foreach ($query->download_category as $value) {
                $category_id_array[] = $value->id;
            }
        } else {
            $page_title = __('backend.新增');
        }

        // 分類選單資料
        $category_select_item = $this->download_category_repository->getOptionItem();

        // 一般表單資料
        $this->tpl_data['form_array_normal'] = [
            'download_title' => [
                'input_type' => 'text',
                'display_name' => __('backend.標題'),
            ],
            'download_category_id[]' => [
                'input_type' => 'select',
                'display_name' => __('backend.分類'),
                'input_value' => $category_id_array,
                'option' => $category_select_item,
                'attribute' => ['multiple' => 'multiple', 'size' => 5],
                'help' => '按著Ctrl點選，可複選多個項目',
            ],
            'post_at' => [
                'input_type' => 'date',
                'display_name' => __('backend.發佈日期'),
            ],
            'file_name' => [
                'input_type' => 'file',
                'display_name' => __('download.檔案'),
                'upload_path' => 'download',
                'value_type' => 'file',
                'multiple' => false,
                'help' => __('backend.檔案上傳說明', ['max_size' => ini_get('upload_max_filesize')]),
            ],
            'download_description' => [
                'input_type' => 'textarea',
                'display_name' => __('backend.說明'),
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
        $res = $this->download_repository->setUpdate($this->download_id);
        if ($res) {
            session()->flash('notify.message', __('backend.資料編輯完成'));
            session()->flash('notify.type', 'success');
            return redirect($this->uri . 'detail?download_id=' . $res);
        } else {
            session()->flash('notify.message', __('backend.資料編輯失敗'));
            session()->flash('notify.type', 'danger');
            return redirect($this->uri . 'update?download_id=' . $this->download_id)->withInput();
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
        $this->download_id = 0;
        return $this->putUpdate();
    }

    /**
     * 細節
     */
    public function detail()
    {
        if ($this->download_id) {
            $download = $this->download_repository->getOne($this->download_id);
            $this->tpl_data['download'] = $download;
            $category_value_str = $download->download_category->implode('category_name', ', ');

            // 表單資料
            $this->tpl_data['form_array'] = [
                'download_title' => [
                    'input_type' => 'value',
                    'display_name' => __('backend.標題'),
                ],
                'download_category_id' => [
                    'input_type' => 'value',
                    'display_name' => __('backend.分類'),
                    'input_value' => $category_value_str
                ],
                'post_at' => [
                    'input_type' => 'value',
                    'display_name' => __('backend.發佈日期'),
                ],
                'file_name' => [
                    'input_type' => 'value',
                    'display_name' => __('download.檔案'),
                    'upload_path' => 'download',
                    'value_type' => 'file'
                ],
                'file_size' => [
                    'input_type' => 'value',
                    'display_name' => __('backend.檔案大小'),
                ],
                'download_description' => [
                    'input_type' => 'value',
                    'display_name' => __('backend.說明'),
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
                $component_datas['dropdown_items']['items']['編輯'] = ['url' => url($this->uri . 'update?download_id=' . $download->id)];
                if (auth()->user()->hasAccess(['delete-' . $this->permission_controller_string])) {
                    $component_datas['dropdown_items']['items']['刪除'] = ['url' => url($this->uri . 'delete?download_id=' . $download->id)];
                }
            }
            if (auth()->user()->hasAccess(['create-' . $this->permission_controller_string])) {
                $component_datas['dropdown_items']['items']['複製'] = ['url' => url($this->uri . 'duplicate?download_id=' . $download->id)];
            }
            $component_datas['dropdown_items']['items']['預覽'] = ['url' => url(config('backend.download.preview_url', 'detail/') . $download->id)];
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
        if ($this->download_id) {
            $version_id = request('version_id', 0);
            if ($version_id) {
                $this->download_category_repository->applyVersion($this->download_id, $version_id);
            }
        }
        return redirect($this->uri . 'detail?download_id=' . $this->role_id);
    }

    /**
     * 刪除
     */
    public function delete()
    {
        if ($this->download_id) {
            $this->download_repository->delete($this->download_id);
        }
        return redirect($this->uri . 'index');
    }
}
