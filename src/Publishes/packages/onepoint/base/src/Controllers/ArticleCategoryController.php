<?php

namespace Onepoint\Base\Controllers;

use App\Http\Controllers\Controller;
use Onepoint\Base\Repositories\ArticleCategoryRepository;
use Onepoint\Dashboard\Traits\ShareMethod;

/**
 * 文章分類
 */
class ArticleCategoryController extends Controller
{
    use ShareMethod;

    /**
     * 建構子
     */
    function __construct(ArticleCategoryRepository $article_category_repository)
    {
        $this->share();
        $this->article_category_repository = $article_category_repository;

        // 預設網址
        $this->uri = config('dashboard.uri') . '/article-category/';
        $this->tpl_data['uri'] = $this->uri;

        // view 路徑
        $this->view_path = 'base::' . config('dashboard.view_path') . '.pages.article-category.';
        $this->article_category_id = request('article_category_id', false);
        $this->tpl_data['article_category_id'] = $this->article_category_id;
    }

    /**
     * 列表
     */
    public function index()
    {
        // 列表基本設定資料
        $component_datas = $this->listPrepare(get_class($this), 'article_category_id');

        // 表格欄位設定
        $component_datas['th'] = [
            ['title' => __('dashboard::backend.標題'), 'class' => ''],
        ];
        $component_datas['column'] = [
            ['type' => 'column', 'class' => '', 'column_name' => 'category_name'],
        ];

        // 關聯資料連結設定
        $component_datas['with'] = [
            [
                'with_count_string' => 'article_count',
                'with_name' => __('base::article.文章'),
                'url' => config('dashboard.uri') . '/article/index',
                'refer_id_string' => 'q_article_category_id',
                'icon' => 'fas fa-list'
            ],
        ];

        // 列表資料查詢
        $component_datas['list'] = $this->article_category_repository->getList($this->article_category_id, config('backend.paginate'));
        $component_datas['use_drag_rearrange'] = true;
        $component_datas['use_sort'] = false;
        // $component_datas['detail_hide'] = false;
        $this->tpl_data['component_datas'] = $component_datas;
        return view($this->view_path . 'index', $this->tpl_data);
    }

    /**
     * 批次處理
     */
    public function putIndex()
    {
        // $settings['file_field'] = 'file_name';
        // $settings['folder'] = 'article-category';
        // $settings['image_scale'] = true;
        $settings['use_version'] = true;
        return $this->batch($this->article_category_repository, $settings);
    }

    /**
     * 編輯
     */
    public function update()
    {
        $this->tpl_data['article_category'] = false;
        if ($this->article_category_id) {
            $query = $this->article_category_repository->getOne($this->article_category_id);
            $this->tpl_data['article_category'] = $query;
        }

        // 表單資料
        $this->tpl_data['form_array'] = [
            'category_name' => [
                'input_type' => 'text',
                'display_name' => __('dashboard::backend.分類名稱'),
            ],
            'category_description' => [
                'input_type' => 'textarea',
                'display_name' => __('base::article.分類說明'),
                'rows' => 5
            ],
            'sort' => [
                'input_type' => 'number',
                'display_name' => __('dashboard::backend.排序'),
            ],
            'status' => [
                'input_type' => 'select',
                'display_name' => __('dashboard::backend.狀態'),
                'option' => ['啟用' => __('dashboard::backend.啟用'), '停用' => __('dashboard::backend.停用')],
            ],
            'note' => [
                'input_type' => 'textarea',
                'display_name' => __('dashboard::backend.備註'),
                'rows' => 5
            ],
        ];
        $this->tpl_data['component_datas']['back_url'] = false;
        return view($this->view_path . 'update', $this->tpl_data);
    }

    /**
     * 送出編輯資料
     */
    public function putUpdate()
    {
        $res = $this->article_category_repository->setUpdate($this->article_category_id);
        if ($res) {
            session()->flash('notify.message', __('dashboard::backend.資料編輯完成'));
            session()->flash('notify.type', 'success');
            if ($this->article_category_id) {
                return redirect($this->uri . 'update?' . $this->base_service->getQueryString(true, true));
            } else {
                return redirect($this->uri . 'detail?article_category_id=' . $res . '&' . $this->base_service->getQueryString(true, true));
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
        $this->article_category_id = 0;
        return $this->putUpdate();
    }

    /**
     * 細節
     */
    public function detail()
    {
        if ($this->article_category_id) {

            // 細節基本設定資料
            $article_category = $this->article_category_repository->getOne($this->article_category_id);
            $this->tpl_data['article_category'] = $article_category;
            $component_datas = $this->detailPrepare(get_class($this), 'article_category_id', $article_category);

            // 表單資料
            $this->tpl_data['form_array'] = [
                'category_name' => [
                    'input_type' => 'value',
                    'display_name' => __('dashboard::backend.分類名稱'),
                ],
                'category_description' => [
                    'input_type' => 'value',
                    'display_name' => __('dashboard::backend.內容'),
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
     * 版本還原
     */
    public function applyVersion()
    {
        if ($this->article_category_id) {
            $version_id = request('version_id', 0);
            if ($version_id) {
                $this->article_category_repository->applyVersion($this->article_category_id, $version_id);
            }
        }
        return redirect($this->uri . 'detail?' . $this->base_service->getQueryString(true, true));
    }

    /**
     * 刪除
     */
    public function delete()
    {
        if ($this->article_category_id) {
            $this->article_category_repository->delete($this->article_category_id);
        }
        return redirect($this->uri . 'detail?' . $this->base_service->getQueryString(true, true));
    }

    /**
     * 修改排序
     */
    public function rearrange()
    {
        if ($this->article_category_id) {
            $this->article_category_repository->rearrange();
        }
        return redirect($this->uri . 'index?' . $this->base_service->getQueryString(true, true));
    }

    /**
     * 拖曳排序
     */
    public function dragSort()
    {
        return $this->article_category_repository->dragRearrange();
    }
}
