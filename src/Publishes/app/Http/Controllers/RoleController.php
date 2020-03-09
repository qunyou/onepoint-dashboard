<?php

namespace App\Http\Controllers;

use Onepoint\Dashboard\Services\BaseService;
use Onepoint\Dashboard\Presenters\RolePresenter;
use App\Repositories\RoleRepository;

/**
 * 群組
 */
class RoleController extends Controller
{
    public $duplicate = false;

    /**
     * 建構子
     */
    function __construct(BaseService $base_services, RoleRepository $role_repository)
    {
        $this->base_services = $base_services;
        $this->tpl_data = $base_services->tpl_data;
        $this->tpl_data['base_services'] = $this->base_services;
        $this->permission_controller_string = get_class($this);
        $this->tpl_data['component_datas']['permission_controller_string'] = $this->permission_controller_string;
        $this->tpl_data['navigation_item'] = config('backend.navigation_item');

        $this->role_repository = $role_repository;

        // 預設網址
        $this->uri = config('dashboard.uri') . '/role/';
        $this->tpl_data['uri'] = $this->uri;
        $this->tpl_data['component_datas']['uri'] = $this->uri;

        // view 路徑
        $this->view_path = config('dashboard.view_path') . '.pages.role.';
        $this->role_id = request('role_id', false);
        $this->tpl_data['role_id'] = $this->role_id;
    }

    /**
     * 列表
     */
    public function index()
    {
        // 列表標題
        if (!$this->tpl_data['trashed']) {
            $this->tpl_data['component_datas']['page_title'] = __('backend.列表');
        } else {
            $this->tpl_data['component_datas']['page_title'] = __('backend.資源回收');
        }

        // 主資料 id query string 字串
        $this->tpl_data['component_datas']['id_string'] = 'role_id';

        // 回列表網址
        $this->tpl_data['component_datas']['back_url'] = url($this->uri . 'index');

        // 表格欄位設定
        $this->tpl_data['component_datas']['th'] = [
            ['title' => __('auth.人員群組'), 'class' => ''],
        ];
        $this->tpl_data['component_datas']['column'] = [
            ['type' => 'column', 'class' => '', 'column_name' => 'role_name'],
        ];

        // 是否使用複製功能
        $this->tpl_data['component_datas']['use_duplicate'] = true;

        // 是否使用版本功能
        $this->tpl_data['component_datas']['use_version'] = true;

        // 是否使用排序功能
        $this->tpl_data['component_datas']['use_sort'] = true;

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
        if (auth()->user()->hasAccess(['create-' . $this->permission_controller_string])) {
            $this->tpl_data['component_datas']['dropdown_items']['items']['匯入'] = ['url' => url($this->uri . 'import')];
        }
        if (auth()->user()->hasAccess(['update-' . $this->permission_controller_string])) {
            $this->tpl_data['component_datas']['dropdown_items']['items']['舊版本'] = ['url' => url($this->uri . 'index?version=true')];
        }
        if (auth()->user()->hasAccess(['delete-' . $this->permission_controller_string])) {
            $this->tpl_data['component_datas']['dropdown_items']['items']['資源回收'] = ['url' => url($this->uri . 'index?trashed=true')];
        }

        // 列表資料查詢
        $this->tpl_data['component_datas']['list'] = $this->role_repository->getList($this->role_id, config('backend.paginate'));
        $this->tpl_data['component_datas']['qs'] = $this->base_services->getQueryString();

        // 預覽按鈕網址
        // $this->tpl_data['component_datas']['preview_url'] = ['url' => url(config('backend.book.preview_url')) . '/', 'column' => 'book_name_slug'];
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
        $settings['use_version'] = true;
        $result = $this->role_repository->batch($settings);
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
        $this->tpl_data['role_presenter'] = new RolePresenter;
        $this->tpl_data['permissions'] = config('backend.permissions');

        // 權限陣列
        $this->tpl_data['role_permissions_array'] = [];
        if ($this->role_id) {
            $page_title =__('auth.編輯人員群組');
            $role = $this->role_repository->getOne($this->role_id);
            $this->tpl_data['role'] = $role;
            $this->tpl_data['role_permissions_array'] = $role->permissions;
        } else {
            $this->tpl_data['role'] = false;
            $page_title = __('auth.新增人員群組');
        }

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
        $role_id = $this->role_repository->setUpdate($this->role_id);
        if ($role_id) {
            return redirect($this->uri . 'index?role_id=' . $role_id);
        } else {
            $this->base_services->rememberInputs();
            return redirect($this->uri . 'update?role_id=' . $this->role_id);
        }
    }

    /**
     * 複製
     */
    public function duplicate()
    {
        $this->duplicate = true;
        return $this->update();
    }

    /**
     * 複製
     */
    public function putDuplicate()
    {
        $this->role_id = 0;
        return $this->putUpdate();
    }

    /**
     * 細節
     */
    public function detail()
    {
        if ($this->role_id) {
            $this->tpl_data['role_presenter'] = new RolePresenter;
            $this->tpl_data['permissions'] = config('backend.permissions');
            $role = $this->role_repository->getOne($this->role_id);
            $this->tpl_data['role'] = $role;
            $this->tpl_data['role_permissions_array'] = $role->permissions;

            // 表單資料
            $this->tpl_data['form_array'] = [
                'role_name' => [
                    'input_type' => 'value',
                    'display_name' => __('auth.群組名稱'),
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
            $component_datas['page_title'] = __('auth.檢視人員群組');
            if ($this->tpl_data['version']) {
                $component_datas['page_title'] .= ' -' . __('backend.版本檢視');
            }
            if ($this->tpl_data['version']) {
                $component_datas['back_url'] = url($this->uri . 'index?role_id=' . request('origin_id') . '&version=true');
            } else {
                $component_datas['back_url'] = url($this->uri . 'index');
            }
            $component_datas['dropdown_items']['btn_align'] = 'float-left';
            if (auth()->user()->hasAccess(['update-' . $this->permission_controller_string])) {
                if ($this->tpl_data['version']) {
                    $component_datas['dropdown_items']['items']['使用此版本'] = ['url' => url($this->uri . 'apply-version?role_id=' . $role->origin_id . '&version_id=' . $role->id)];
                } else {
                    $component_datas['dropdown_items']['items']['編輯'] = ['url' => url($this->uri . 'update?role_id=' . $role->id)];
                    if (auth()->user()->hasAccess(['delete-' . $this->permission_controller_string])) {
                        $component_datas['dropdown_items']['items']['刪除'] = ['url' => url($this->uri . 'delete?role_id=' . $role->id)];
                    }
                }
            }
            if (auth()->user()->hasAccess(['create-' . $this->permission_controller_string])) {
                if (!$this->tpl_data['version']) {
                    $component_datas['dropdown_items']['items']['複製'] = ['url' => url($this->uri . 'duplicate?role_id=' . $role->id)];
                }
            }
            // $component_datas['dropdown_items']['items']['預覽'] = ['url' => url(config('backend.role.preview_url', 'detail/') . $role->id)];
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
        if ($this->role_id) {
            $version_id = request('version_id', 0);
            if ($version_id) {
                $this->role_repository->applyVersion($this->role_id, $version_id);
            }
        }
        return redirect($this->uri . 'detail?role_id=' . $this->role_id);
    }

    /**
     * 刪除
     */
    public function delete()
    {
        if ($this->role_id) {
            $this->role_repository->delete($this->role_id);
        }
        return redirect($this->uri . 'index');
    }

    /**
     * 修改排序
     */
    public function rearrange()
    {
        if ($this->role_id) {
            $this->role_repository->rearrange();
        }
        return redirect($this->uri . 'index');
    }
}
