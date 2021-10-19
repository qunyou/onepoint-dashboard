<?php

namespace Onepoint\Dashboard\Controllers;

use App\Http\Controllers\Controller;
use Onepoint\Dashboard\Presenters\RolePresenter;
use Onepoint\Dashboard\Repositories\RoleRepository;
use Onepoint\Dashboard\Traits\ShareMethod;

/**
 * 群組
 */
class RoleController extends Controller
{
    use ShareMethod;

    /**
     * 建構子
     */
    public function __construct()
    {
        $this->share('role_id', 'role', 'pages.role', 'dashboard::dashboard.');
    }

    /**
     * 列表
     */
    public function index()
    {
        $component_datas = $this->listPrepare();

        // 表格欄位設定
        $component_datas['th'] = [
            ['title' => __('dashboard::auth.人員群組')],
        ];
        $component_datas['column'] = [
            ['type' => 'column', 'column_name' => 'role_name'],
        ];

        // 列表資料查詢
        $role_repository = new RoleRepository;
        $component_datas['list'] = $role_repository->getList($this->role_id, config('backend.paginate'));
        $component_datas['use_sort'] = false;
        $this->tpl_data['component_datas'] = $component_datas;
        return view($this->view_path . 'index', $this->tpl_data);
    }

    /**
     * 批次處理
     */
    public function putIndex()
    {
        $settings['use_version'] = true;
        $role_repository = new RoleRepository;
        return $this->batch($role_repository, $settings);
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
            $role_repository = new RoleRepository;
            $role = $role_repository->getOne($this->role_id);
            $this->tpl_data['role'] = $role;
            $this->tpl_data['role_permissions_array'] = $role->permissions;
        } else {
            $this->tpl_data['role'] = false;
        }
        return view($this->view_path . 'update', $this->tpl_data);
    }

    /**
     * 送出編輯資料
     */
    public function putUpdate()
    {
        $role_repository = new RoleRepository;
        $role_id = $role_repository->setUpdate($this->role_id);
        if ($role_id) {
            session()->flash('notify.message', '資料編輯完成');
            session()->flash('notify.type', 'success');
            return redirect($this->uri . 'update?' . $this->base_service->getQueryString(true, true));
            // return redirect($this->uri . 'index?role_id=' . $role_id);
        } else {
            session()->flash('notify.message', '資料編輯失敗');
            session()->flash('notify.type', 'danger');
            // $this->base_service->rememberInputs();
            // return redirect($this->uri . 'update?role_id=' . $this->role_id);
            return redirect($this->uri . 'update?' . $this->base_service->getQueryString(true, true))->withInput();
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
    public function putDuplicate()
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
                    'display_name' => __('dashboard::auth.群組名稱'),
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

            // 樣版資料
            $component_datas['page_title'] = __('dashboard::auth.檢視人員群組');
            if ($this->tpl_data['version']) {
                $component_datas['page_title'] .= ' -' . __('dashboard::backend.版本檢視');
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
