<?php

namespace Onepoint\Base\Controllers;

use App\Http\Controllers\Controller;
use Onepoint\Dashboard\Traits\ShareMethod;
use Onepoint\Base\Repositories\ArticleCategoryRepository;

/**
 * 文章分類
 * 1.0.01
 * packages/onepoint/base/src/Controllers/ArticleCategoryController.php
 */
class ArticleCategoryController extends Controller
{
    use ShareMethod;

    /**
     * 建構子
     */
    public function __construct()
    {
        $this->share('article_category_id', 'article-category', 'pages.article-category');
    }

    /**
     * 列表
     */
    public function index()
    {
        $component_datas = $this->listPrepare();

        // 表格欄位設定
        $component_datas['th'] = [
            ['title' => __('dashboard::backend.標題')],
        ];
        $component_datas['column'] = [
            ['type' => 'column', 'column_name' => 'category_name'],
        ];

        // 關聯資料連結設定
        $component_datas['with'] = [
            [
                'with_count_string' => 'article_count',
                'with_name' => __('base::article.文章'),
                'url' => config('dashboard.uri') . '/article/index',
                'refer_id_string' =>'q_article_category_id',
                'icon' => 'fas fa-list',
            ],
        ];

        // 列表資料查詢
        $article_category_repository = new ArticleCategoryRepository;
        $component_datas['list'] = $article_category_repository->getList($this->article_category_id, config('backend.paginate'));
        $component_datas['use_drag_rearrange'] = true;
        $component_datas['use_sort'] = false;
        $component_datas['detail_hide'] = false;
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
        $article_category_repository = new ArticleCategoryRepository;
        $result = $article_category_repository->batch($settings);
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
        $this->tpl_data['article_category'] = false;
        if ($this->article_category_id) {
            // $page_title = __('dashboard::backend.編輯');
            $article_category_repository = new ArticleCategoryRepository;
            $query = $article_category_repository->getOne($this->article_category_id);
            $this->tpl_data['article_category'] = $query;
        } else {
            // $page_title = __('dashboard::backend.新增');
        }

        // 複製
        // if (isset($this->tpl_data['duplicate']) && $this->tpl_data['duplicate']) {
        //     $page_title = __('dashboard::backend.複製');
        // }

        // 表單資料
        $this->tpl_data['form_array'] = [
            'category_name' => [
                'input_type' => 'text',
                'display_name' => __('dashboard::backend.分類名稱'),
            ],
            'sort' => [
                'input_type' => 'number',
                'display_name' => __('dashboard::backend.排序'),
            ],
            'status' => [
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
        return view($this->view_path . 'update', $this->tpl_data);
    }

    /**
     * 送出編輯資料
     */
    public function putUpdate()
    {
        $article_category_repository = new ArticleCategoryRepository;
        $res = $article_category_repository->setUpdate($this->article_category_id);
        if ($res) {
            session()->flash('notify.message', __('dashboard::backend.資料編輯完成'));
            session()->flash('notify.type', 'success');
            return redirect($this->uri . 'update?article_category_id=' . $res);
        } else {
            session()->flash('notify.message', __('dashboard::backend.資料編輯失敗'));
            session()->flash('notify.type', 'danger');
            return redirect($this->uri . 'update?article_category_id=' . $res)->withInput();
        }
    }

    /**
     * 細節
     */
    public function detail()
    {
        $component_datas = $this->detailPrepare();
        if ($this->article_category_id) {
            $article_category_repository = new ArticleCategoryRepository;
            $article_category = $article_category_repository->getOne($this->article_category_id);
            $this->tpl_data['article_category'] = $article_category;

            // 表單資料
            $this->tpl_data['form_array'] = [
                'category_name' => [
                    'input_type' => 'value',
                    'display_name' => __('dashboard::backend.分類名稱'),
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
                $article_category_repository = new ArticleCategoryRepository;
                $article_category_repository->applyVersion($this->article_category_id, $version_id);
            }
        }
        return redirect($this->uri . 'detail?article_category_id=' . $this->article_category_id);
    }

    /**
     * 刪除
     */
    public function delete()
    {
        if ($this->article_category_id) {
            $article_category_repository = new ArticleCategoryRepository;
            $article_category_repository->delete($this->article_category_id);
        }
        return redirect($this->uri . 'index');
    }

    /**
     * 修改排序
     */
    public function rearrange()
    {
        if ($this->article_category_id) {
            $article_category_repository = new ArticleCategoryRepository;
            $article_category_repository->rearrange();
        }
        return redirect($this->uri . 'index');
    }

    /**
     * 拖曳排序
     */
    public function dragSort()
    {
        $article_category_repository = new ArticleCategoryRepository;
        return $article_category_repository->dragRearrange();
    }
}
