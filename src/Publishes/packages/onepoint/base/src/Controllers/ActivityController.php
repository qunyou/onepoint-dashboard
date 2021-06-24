<?php

namespace Onepoint\Base\Controllers;

use App\Http\Controllers\Controller;
use Onepoint\Base\Repositories\ActivityRepository;
use Onepoint\Dashboard\Traits\ShareMethod;

/**
 * 活動
 */
class ActivityController extends Controller
{
    use ShareMethod;

    /**
     * 建構子
     */
    public function __construct(ActivityRepository $activity_repository)
    {
        $this->share();
        $this->activity_repository = $activity_repository;

        // 預設網址
        $this->uri = config('dashboard.uri') . '/activity/';
        $this->tpl_data['uri'] = $this->uri;

        // view 路徑
        $this->view_path = 'base::' . config('dashboard.view_path') . '.pages.activity.';
        $this->activity_id = request('activity_id', false);
        $this->tpl_data['activity_id'] = $this->activity_id;
    }

    /**
     * 列表
     */
    public function index()
    {
        $component_datas = $this->listPrepare();
        $permission_controller_string = get_class($this);
        $component_datas['permission_controller_string'] = $permission_controller_string;
        $component_datas['uri'] = $this->uri;
        $component_datas['back_url'] = url($this->uri . 'index');

        // 主資料 id query string 字串
        $component_datas['id_string'] = 'activity_id';

        // 表格欄位設定
        $component_datas['th'] = [
            ['title' => __('dashboard::backend.標題')],
            ['title' => '開始時間'],
            ['title' => '結束時間'],
        ];
        // 'click' => ['class' => 'badge badge-secondary', 'badge_title' => '點擊:']
        $component_datas['column'] = [
            ['type' => 'column', 'column_name' => 'activity_title'],
            ['type' => 'column', 'column_name' => 'start_at'],
            ['type' => 'column', 'column_name' => 'end_at'],
        ];

        // 權限設定
        if (auth()->user()->hasAccess(['create-' . $permission_controller_string])) {
            $component_datas['add_url'] = url($this->uri . 'update');
        }
        if (auth()->user()->hasAccess(['update-' . $permission_controller_string])) {
            if (!auth()->user()->hasAccess(['delete-' . $permission_controller_string])) {
                $component_datas['footer_delete_hide'] = true;
            }
        } else {
            $component_datas['footer_dropdown_hide'] = true;
            $component_datas['footer_sort_hide'] = true;
        }
        if (auth()->user()->hasAccess(['update-' . $permission_controller_string])) {
            $component_datas['dropdown_items']['items']['舊版本'] = ['url' => url($this->uri . 'index?version=true')];
        }
        if (auth()->user()->hasAccess(['delete-' . $permission_controller_string])) {
            $component_datas['dropdown_items']['items']['資源回收'] = ['url' => url($this->uri . 'index?trashed=true')];
        }

        // 列表資料查詢
        $component_datas['list'] = $this->activity_repository->getList($this->activity_id, config('backend.paginate'));
        $component_datas['use_sort'] = false;
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
        $settings['folder'] = 'activity';
        $settings['image_scale'] = true;
        $settings['use_version'] = true;
        $result = $this->activity_repository->batch($settings);
        switch ($result['batch_method']) {
            case 'restore':
            case 'force_delete':
                $back_url_str = 'index?trashed=true';
                break;
            default:
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
        $this->tpl_data['activity'] = false;
        if ($this->activity_id) {
            $page_title = __('base::activity.活動編輯');
            $query = $this->activity_repository->getOne($this->activity_id);

            // 複製
            if (isset($this->tpl_data['duplicate']) && $this->tpl_data['duplicate']) {
                $page_title = __('dashboard::backend.複製');
            }
            $this->tpl_data['activity'] = $query;
        } else {
            $page_title = __('base::activity.活動新增');
        }

        // 一般表單資料
        $this->tpl_data['form_array_normal'] = [
            'activity_title' => [
                'input_type' => 'text',
                'display_name' => __('base::activity.活動標題'),
            ],
            'start_at' => [
                'input_type' => 'date',
                'display_name' => __('base::activity.開始時間'),
            ],
            'end_at' => [
                'input_type' => 'date',
                'display_name' => __('base::activity.結束時間'),
            ],
            'activity_content' => [
                'input_type' => 'textarea',
                'display_name' => __('base::activity.活動內容'),
                'base64_decode' => true,
            ],
        ];

        // 進階表單資料
        $this->tpl_data['form_array_advanced'] = [
            'sort' => [
                'input_type' => 'number',
                'display_name' => __('dashboard::backend.排序'),
            ],
            'enable' => [
                'input_type' => 'select',
                'display_name' => __('dashboard::backend.狀態'),
                'option' => config('backend.status_item'),
            ],
            'note' => [
                'input_type' => 'textarea',
                'display_name' => __('dashboard::backend.備註'),
                'rows' => 5,
            ],
        ];

        // 樣版資料
        $this->tpl_data['component_datas']['page_title'] = $page_title;
        $this->tpl_data['component_datas']['back_url'] = false;
        return view($this->view_path . 'update', $this->tpl_data);
    }

    /**
     * 送出編輯資料
     */
    public function putUpdate()
    {
        $res = $this->activity_repository->setUpdate($this->activity_id);
        if ($res) {
            session()->flash('notify.message', __('dashboard::backend.資料編輯完成'));
            session()->flash('notify.type', 'success');
            if ($this->activity_id) {
                return redirect($this->uri . 'update?' . $this->base_service->getQueryString(true, true));
            } else {
                return redirect($this->uri . 'update?activity_id=' . $res . '&' . $this->base_service->getQueryString(true, true));
            }
        } else {
            session()->flash('notify.message', __('dashboard::backend.資料編輯失敗'));
            session()->flash('notify.type', 'danger');
            return redirect($this->uri . 'update?' . $this->base_service->getQueryString(true, true))->withInput();
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
    public function putDuplicate()
    {
        $this->activity_id = 0;
        return $this->putUpdate();
    }

    /**
     * 細節
     */
    public function detail()
    {
        if ($this->activity_id) {
            $activity = $this->activity_repository->getOne($this->activity_id);
            $this->tpl_data['activity'] = $activity;

            // 表單資料
            $this->tpl_data['form_array'] = [
                'activity_title' => [
                    'input_type' => 'value',
                    'display_name' => __('base::activity.活動標題'),
                ],
                'start_at' => [
                    'input_type' => 'value',
                    'display_name' => __('base::activity.開始時間'),
                ],
                'end_at' => [
                    'input_type' => 'value',
                    'display_name' => __('base::activity.結束時間'),
                ],
                'activity_content' => [
                    'input_type' => 'value',
                    'display_name' => __('base::activity.活動內容'),
                ],
                'sort' => [
                    'input_type' => 'value',
                    'display_name' => __('dashboard::backend.排序'),
                ],
                'enable' => [
                    'input_type' => 'value',
                    'display_name' => __('dashboard::backend.狀態'),
                ],
                'note' => [
                    'input_type' => 'value',
                    'display_name' => __('dashboard::backend.備註'),
                ],
            ];

            // 樣版資料
            $component_datas['page_title'] = __('dashboard::backend.檢視');
            // $component_datas['back_url'] = url($this->uri . 'index?page=' . session('page', 1));
            $component_datas['back_url'] = false;
            $component_datas['dropdown_items']['btn_align'] = 'float-left';
            if (auth()->user()->hasAccess(['update-' . $this->permission_controller_string])) {
                $component_datas['dropdown_items']['items']['編輯'] = ['url' => url($this->uri . 'update?activity_id=' . $activity->id)];
                if (auth()->user()->hasAccess(['delete-' . $this->permission_controller_string])) {
                    $component_datas['dropdown_items']['items']['刪除'] = ['url' => url($this->uri . 'delete?activity_id=' . $activity->id)];
                }
            }
            if (auth()->user()->hasAccess(['create-' . $this->permission_controller_string])) {
                $component_datas['dropdown_items']['items']['複製'] = ['url' => url($this->uri . 'duplicate?activity_id=' . $activity->id)];
            }
            if (config('backend.activity.preview_url', false)) {
                $component_datas['dropdown_items']['items']['預覽'] = ['url' => url(config('backend.activity.preview_url') . '/' . $activity->id)];
            }
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
        if ($this->activity_id) {
            $version_id = request('version_id', 0);
            if ($version_id) {
                $this->activity_repository->applyVersion($this->activity_id, $version_id);
            }
        }
        return redirect($this->uri . 'detail?' . $this->base_service->getQueryString(true, true));
    }

    /**
     * 刪除
     */
    public function delete()
    {
        if ($this->activity_id) {
            $this->activity_repository->delete($this->activity_id);
        }
        return redirect($this->uri . 'detail?' . $this->base_service->getQueryString(true, true));
    }

    /**
     * 修改排序
     */
    public function rearrange()
    {
        if ($this->activity_id) {
            $this->activity_repository->rearrange();
        }
        return redirect($this->uri . 'detail?' . $this->base_service->getQueryString(true, true));
    }
}
