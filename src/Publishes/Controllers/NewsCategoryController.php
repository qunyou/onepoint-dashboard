<?php

namespace App\Http\Controllers;

use Onepoint\Dashboard\Services\BaseService;
use App\Repositories\NewsCategoryRepository;

/**
 * 新聞分類
 */
class NewsCategoryController extends Controller
{
    /**
     * 建構子
     */
    function __construct(BaseService $base_services, NewsCategoryRepository $news_category_repository)
    {
        $this->base_services = $base_services;
        $this->tpl_data = $base_services->tpl_data;
        $this->tpl_data['base_services'] = $this->base_services;
        $this->news_category_repository = $news_category_repository;
        $this->permission_controller_string = class_basename(get_class($this));
        $this->tpl_data['permission_controller_string'] = $this->permission_controller_string;

        // 預設網址
        $this->uri = config('dashboard.uri') . '/news-category/';
        $this->tpl_data['uri'] = $this->uri;

        // view 路徑
        $this->view_path = config('dashboard.view_path') . '.pages.news-category.';

        // 主功能標題
        $this->tpl_data['page_header'] = __('article.新聞分類');
        $this->news_category_id = request('news_category_id', false);
        $this->tpl_data['news_category_id'] = $this->news_category_id;

        // 當前分頁
        $this->page = request('page', 1);
        $this->tpl_data['page'] = $this->page;
    }

    /**
     * 列表
     */
    public function index()
    {
        $this->tpl_data['list'] = $this->news_category_repository->getList($this->news_category_id, config('backend.paginate'));

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
        $settings['use_version'] = true;
        $result = $this->news_category_repository->batch($settings);
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
        $this->tpl_data['news_category'] = false;
        if ($this->news_category_id) {
            $page_title = __('backend.編輯');
            $query = $this->news_category_repository->getOne($this->news_category_id);

            // 複製時不顯示圖片
            if ($this->tpl_data['duplicate']) {
                $query->file_name = '';
                $page_title = __('backend.複製');
            }
            $this->tpl_data['news_category'] = $query;
        } else {
            $page_title = __('backend.新增');
        }

        // 一般表單資料
        // 表單資料
        $this->tpl_data['form_array'] = [
            'category_name' => [
                'input_type' => 'text',
                'display_name' => __('backend.分類名稱'),
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
                'rows' => 5
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
        $news_category_id = $this->news_category_repository->setUpdate($this->news_category_id);
        if ($news_category_id) {
            session()->flash('notify.message', __('backend.資料編輯完成'));
            session()->flash('notify.type', 'success');
            return redirect($this->uri . 'index?news_category_id=' . $news_category_id);
        } else {
            session()->flash('notify.message', __('backend.資料編輯失敗'));
            session()->flash('notify.type', 'danger');
            return redirect($this->uri . 'update?news_category_id=' . $this->news_category_id);
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
        $this->news_category_id = 0;
        return $this->putUpdate();
    }

    /**
     * 細節
     */
    public function detail()
    {
        if ($this->news_category_id) {
            $news_category = $this->news_category_repository->getOne($this->news_category_id);
            $this->tpl_data['news_category'] = $news_category;

            // 表單資料
            $this->tpl_data['form_array'] = [
                'category_name' => [
                    'input_type' => 'value',
                    'display_name' => __('backend.分類名稱'),
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
                $component_datas['dropdown_items']['items']['編輯'] = ['url' => url($this->uri . 'update?news_category_id=' . $news_category->id)];
            }
            if (auth()->user()->hasAccess(['create-' . $this->permission_controller_string])) {
                $component_datas['dropdown_items']['items']['複製'] = ['url' => url($this->uri . 'duplicate?news_category_id=' . $news_category->id)];
            }
            if (auth()->user()->hasAccess(['delete-' . $this->permission_controller_string])) {
                $component_datas['dropdown_items']['items']['刪除'] = ['url' => url($this->uri . 'delete?news_category_id=' . $news_category->id)];
            }
            $component_datas['dropdown_items']['items']['預覽'] = ['url' => url(config('backend.news.preview_url', 'detail/') . $news_category->id)];
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
        if ($this->news_category_id) {
            $version_id = request('version_id', 0);
            if ($version_id) {
                $this->article_category_repository->applyVersion($this->news_category_id, $version_id);
            }
        }
        return redirect($this->uri . 'detail?news_category_id=' . $this->role_id);
    }

    /**
     * 刪除
     */
    public function delete()
    {
        if ($this->news_category_id) {
            $this->news_category_repository->delete($this->news_category_id);
        }
        return redirect($this->uri . 'index');
    }
}
