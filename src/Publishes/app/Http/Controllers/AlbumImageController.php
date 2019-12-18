<?php

namespace App\Http\Controllers;

use Onepoint\Dashboard\Services\BaseService;
use Onepoint\Dashboard\Services\ImageService;
use Onepoint\Dashboard\Services\FileService;
use App\Repositories\AlbumImageRepository;
use App\Repositories\AlbumRepository;

/**
 * 相簿
 */
class AlbumImageController extends Controller
{
    /**
     * 建構子
     */
    function __construct(BaseService $base_services, AlbumImageRepository $album_image_repository, AlbumRepository $album_repository)
    {
        $this->base_services = $base_services;
        $this->tpl_data = $base_services->tpl_data;
        $this->tpl_data['base_services'] = $this->base_services;
        $this->album_image_repository = $album_image_repository;
        $this->album_repository = $album_repository;
        $this->permission_controller_string = class_basename(get_class($this));
        $this->tpl_data['permission_controller_string'] = $this->permission_controller_string;

        // 預設網址
        $this->uri = config('dashboard.uri') . '/album-image/';
        $this->tpl_data['uri'] = $this->uri;

        // view 路徑
        $this->view_path = config('dashboard.view_path') . '.pages.album-image.';

        // 主功能標題
        $this->tpl_data['page_header'] = __('album.相片管理');
        $this->album_image_id = request('album_image_id', false);
        $this->tpl_data['album_image_id'] = $this->album_image_id;

        $this->album_id = request('album_id', false);
        $this->tpl_data['album_id'] = $this->album_id;
    }

    /**
     * 列表
     */
    public function index(ImageService $image_service)
    {
        $this->tpl_data['image_service'] = $image_service;
        $this->tpl_data['list'] = $this->album_image_repository->getList($this->album_id, config('backend.paginate'));

        // 樣版資料
        if (!$this->tpl_data['trashed']) {
            $component_datas['page_title'] = __('backend.列表');
        } else {
            $component_datas['page_title'] = __('backend.資源回收');
        }
        $component_datas['back_url'] = url($this->uri . 'index');
        $this->tpl_data['footer_delete_hide'] = false;
        if (auth()->user()->hasAccess(['create-' . $this->permission_controller_string])) {
            $component_datas['add_url'] = url($this->uri . 'update?album_id=' . $this->album_id);
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

        // 更新按鈕網址增加參數
        $this->tpl_data['update_url_append_string'] = '&album_id=' . $this->album_id;
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
        $settings['folder'] = 'album';
        $settings['image_scale'] = true;
        $settings['use_version'] = true;
        $result = $this->album_image_repository->batch($settings);
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
        $this->tpl_data['album'] = false;
        
        // 相簿值陣列
        $album_id_array = [];
        if ($this->album_image_id) {
            $page_title = __('backend.編輯');
            $query = $this->album_image_repository->getOne($this->album_image_id);

            // 複製時不顯示圖片
            if (isset($this->tpl_data['duplicate']) && $this->tpl_data['duplicate']) {
                $query->file_name = '';
                $page_title = __('backend.複製');
            }
            $this->tpl_data['album_image'] = $query;

            // 刪除附檔
            $delete_file = request('delete_file', false);
            if ($delete_file) {
                ImageService::delete($query->file_name, true, 'album');
                $query->file_name = '';
                $query->save();
                // return back();
            }
            foreach ($query->album as $value) {
                $album_id_array[] = $value->id;
            }
        } else {
            $page_title = __('backend.新增');
            $album_id_array[] = $this->album_id;
        }

        // 分類選單資料
        $album_select_item = $this->album_repository->getOptionItem();

        // 一般表單資料
        $this->tpl_data['form_array_normal'] = [
            'album_images_title' => [
                'input_type' => 'text',
                'display_name' => __('backend.標題'),
            ],
            'album_id[]' => [
                'input_type' => 'select',
                'display_name' => __('album.相簿'),
                'input_value' => $album_id_array,
                'option' => $album_select_item,
                'attribute' => ['multiple' => 'multiple', 'size' => 5],
                'help' => '按著Ctrl點選，可複選多個項目',
            ],
            'file_name' => [
                'input_type' => 'file',
                'display_name' => __('backend.圖片'),
                'upload_path' => 'album',
                'value_type' => 'image',
                'image_attribute' => ['style' => 'width:200px;'],
                'image_thumb' => false,
                'multiple' => false,
                'help' => __('backend.圖片上傳說明', ['max_size' => ini_get('upload_max_filesize')]),
            ],
            'album_images_content' => [
                'input_type' => 'tinymce',
                'display_name' => __('album.相片說明'),
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
        $res = $this->album_image_repository->setUpdate($this->album_image_id);
        if (is_array($this->album_id)) {
            $this->album_id = $this->album_id[0];
        }
        if ($res) {
            session()->flash('notify.message', __('backend.資料編輯完成'));
            session()->flash('notify.type', 'success');
            return redirect($this->uri . 'detail?album_image_id=' . $res . '&album_id=' . $this->album_id);
        } else {
            session()->flash('notify.message', __('backend.資料編輯失敗'));
            session()->flash('notify.type', 'danger');
            return redirect($this->uri . 'update?album_image_id=' . $this->album_image_id . '&album_id=' . $this->album_id)->withInput();
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
        $this->album_image_id = 0;
        return $this->putUpdate();
    }

    /**
     * 細節
     */
    public function detail()
    {
        if ($this->album_image_id) {
            $album_image = $this->album_image_repository->getOne($this->album_image_id);
            $this->tpl_data['album_image'] = $album_image;
            $album_value_str = $album_image->album->implode('album_title', ', ');

            // 表單資料
            $this->tpl_data['form_array'] = [
                'album_images_title' => [
                    'input_type' => 'value',
                    'display_name' => __('backend.標題'),
                ],
                'album_id' => [
                    'input_type' => 'value',
                    'display_name' => __('album.相簿'),
                    'input_value' => $album_value_str
                ],
                'file_name' => [
                    'input_type' => 'value',
                    'display_name' => __('backend.圖片'),
                    'upload_path' => 'album',
                    'value_type' => 'image',
                    'image_attribute' => ['style' => 'width:200px;'],
                ],
                'album_images_content' => [
                    'input_type' => 'value',
                    'display_name' => __('album.相片說明'),
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
            // $component_datas['back_url'] = url($this->uri . 'index?album_id=' . $album_image->id);
            $component_datas['back_url'] = url($this->uri . 'index?album_id=' . $this->album_id);
            $component_datas['dropdown_items']['btn_align'] = 'float-left';
            if (auth()->user()->hasAccess(['update-' . $this->permission_controller_string])) {
                $component_datas['dropdown_items']['items']['編輯'] = ['url' => url($this->uri . 'update?album_image_id=' . $album_image->id)];
                if (auth()->user()->hasAccess(['delete-' . $this->permission_controller_string])) {
                    $component_datas['dropdown_items']['items']['刪除'] = ['url' => url($this->uri . 'delete?album_image_id=' . $album_image->id)];
                }
            }
            if (auth()->user()->hasAccess(['create-' . $this->permission_controller_string])) {
                $component_datas['dropdown_items']['items']['複製'] = ['url' => url($this->uri . 'duplicate?album_image_id=' . $album_image->id)];
            }
            $component_datas['dropdown_items']['items']['預覽'] = ['url' => url(config('backend.album.preview_url', 'detail/') . $album_image->id)];
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
        if ($this->album_image_id) {
            $version_id = request('version_id', 0);
            if ($version_id) {
                $this->album_image_repository->applyVersion($this->album_image_id, $version_id);
            }
        }
        return redirect($this->uri . 'detail?album_image_id=' . $this->role_id);
    }

    /**
     * 刪除
     */
    public function delete()
    {
        if ($this->album_image_id) {
            $this->album_image_repository->delete($this->album_image_id);
        }
        return redirect($this->uri . 'index');
    }
}
