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
        $this->role_repository = $role_repository;
        $this->permission_controller_string = class_basename(get_class($this));
        $this->tpl_data['permission_controller_string'] = $this->permission_controller_string;

        // 預設網址
        $this->uri = config('dashboard.uri') . '/role/';
        $this->tpl_data['uri'] = $this->uri;

        // view 路徑
        $this->view_path = config('dashboard.view_path') . '.pages.role.';

        // 主功能標題
        $this->tpl_data['page_header'] = __('auth.人員群組');
        $this->role_id = request('role_id', false);
        $this->tpl_data['role_id'] = $this->role_id;

        // 當前分頁
        $this->page = request('page', 1);
        $this->tpl_data['page'] = $this->page;
    }

    /**
     * 列表
     */
    public function index()
    {
        $this->tpl_data['list'] = $this->role_repository->getList($this->role_id, config('backend.paginate'));

        // 樣版資料
        if (!$this->tpl_data['trashed']) {
            $component_datas['page_title'] = __('auth.人員群組列表');
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
        // if (auth()->user()->hasAccess(['update-' . $this->permission_controller_string])) {
        //     $component_datas['dropdown_items']['items']['舊版本'] = ['url' => url($this->uri . 'index?version=true')];
        // }
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
            $this->tpl_data['page_title'] = __('auth.編輯人員群組');
            $role = $this->role_repository->getOne($this->role_id);
            $this->tpl_data['role'] = $role;
            $this->tpl_data['role_permissions_array'] = $role->permissions;
        } else {
            $this->tpl_data['role'] = false;
            $this->tpl_data['page_title'] = __('auth.新增人員群組');
        }
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
}
