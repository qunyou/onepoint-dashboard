<?php

namespace App\Http\Controllers;

use Cache;
use Onepoint\Dashboard\Services\BaseService;
use Onepoint\Dashboard\Services\ImageService;
use Onepoint\Dashboard\Services\FileService;
use App\Repositories\ArticleRepository;
use App\Repositories\ArticleCategoryRepository;

/**
 * 文章
 */
class ArticleController extends Controller
{
    /**
     * 建構子
     */
    function __construct(BaseService $base_services, ArticleRepository $article_repository, ArticleCategoryRepository $article_category_repository)
    {
        $this->base_services = $base_services;
        $this->tpl_data = $base_services->tpl_data;
        $this->tpl_data['base_services'] = $this->base_services;
        $this->article_repository = $article_repository;
        $this->article_category_repository = $article_category_repository;
        $this->permission_controller_string = class_basename(get_class($this));
        $this->tpl_data['permission_controller_string'] = $this->permission_controller_string;

        // 預設網址
        $this->uri = config('dashboard.uri') . '/article/';
        $this->tpl_data['uri'] = $this->uri;

        // view 路徑
        $this->view_path = config('dashboard.view_path') . '.pages.article.';

        // 主功能標題
        $this->tpl_data['page_header'] = __('article.文章管理');
        $this->article_id = request('article_id', false);
        $this->tpl_data['article_id'] = $this->article_id;
    }

    /**
     * 列表
     */
    public function index(ImageService $image_service)
    {
        $this->tpl_data['image_service'] = $image_service;
        $this->tpl_data['list'] = $this->article_repository->getList($this->article_id, config('backend.paginate'));

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
        $settings['folder'] = 'article';
        $settings['image_scale'] = true;
        $settings['use_version'] = true;
        $result = $this->article_repository->batch($settings);
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
        $this->tpl_data['article'] = false;
        
        // 分類值陣列
        $category_id_array = [];
        if ($this->article_id) {
            $page_title = __('backend.編輯');
            $query = $this->article_repository->getOne($this->article_id);

            // 複製時不顯示圖片
            if (isset($this->tpl_data['duplicate']) && $this->tpl_data['duplicate']) {
                $query->file_name = '';
                $page_title = __('backend.複製');
            }
            $this->tpl_data['article'] = $query;

            // 刪除附檔
            $delete_file = request('delete_file', false);
            if ($delete_file) {
                ImageService::delete($query->file_name, true, 'article');
                $query->file_name = '';
                $query->save();
                // return back();
            }
            foreach ($query->article_category as $value) {
                $category_id_array[] = $value->id;
            }
        } else {
            $page_title = __('backend.新增');
        }

        // 分類選單資料
        $category_select_item = $this->article_category_repository->getOptionItem();

        // 一般表單資料
        $this->tpl_data['form_array_normal'] = [
            'article_title' => [
                'input_type' => 'text',
                'display_name' => __('backend.標題'),
            ],
            'article_category_id[]' => [
                'input_type' => 'select',
                'display_name' => __('backend.分類'),
                'input_value' => $category_id_array,
                'option' => $category_select_item,
                'attribute' => ['multiple' => 'multiple', 'size' => 5],
                'help' => '按著Ctrl點選，可複選多個項目',
            ],
            'file_name' => [
                'input_type' => 'file',
                'display_name' => __('backend.圖片'),
                'upload_path' => 'article',
                'value_type' => 'image',
                'image_attribute' => ['style' => 'width:200px;'],
                'image_thumb' => false,
                'multiple' => false,
                'help' => __('backend.圖片上傳說明', ['max_size' => ini_get('upload_max_filesize')]),
            ],
            'summary' => [
                'input_type' => 'text',
                'display_name' => __('backend.列表摘要'),
            ],
            'post_at' => [
                'input_type' => 'date',
                'display_name' => __('backend.發佈日期'),
            ],
            'article_content' => [
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
            'member_only' => [
                'input_type' => 'select',
                'display_name' => __('backend.僅供會員檢視'),
                'option' => config('backend.status_item'),
            ],
            'url' => [
                'input_type' => 'text',
                'display_name' => __('backend.外連網址'),
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
        $res = $this->article_repository->setUpdate($this->article_id);
        if ($res) {
            session()->flash('notify.message', __('backend.資料編輯完成'));
            session()->flash('notify.type', 'success');
            return redirect($this->uri . 'detail?article_id=' . $res);
        } else {
            session()->flash('notify.message', __('backend.資料編輯失敗'));
            session()->flash('notify.type', 'danger');
            return redirect($this->uri . 'update?article_id=' . $this->article_id)->withInput();
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
        $this->article_id = 0;
        return $this->putUpdate();
    }

    /**
     * 細節
     */
    public function detail()
    {
        if ($this->article_id) {
            $article = $this->article_repository->getOne($this->article_id);
            $this->tpl_data['article'] = $article;
            $category_value_str = $article->article_category->implode('category_name', ', ');

            // 表單資料
            $this->tpl_data['form_array'] = [
                'article_title' => [
                    'input_type' => 'value',
                    'display_name' => __('backend.標題'),
                ],
                'article_category_id' => [
                    'input_type' => 'value',
                    'display_name' => __('backend.分類'),
                    'input_value' => $category_value_str
                ],
                'file_name' => [
                    'input_type' => 'value',
                    'display_name' => __('backend.圖片'),
                    'upload_path' => 'article',
                    'value_type' => 'image',
                    'image_attribute' => ['style' => 'width:200px;'],
                ],
                'summary' => [
                    'input_type' => 'value',
                    'display_name' => __('backend.列表摘要'),
                ],
                'post_at' => [
                    'input_type' => 'value',
                    'display_name' => __('backend.發佈日期'),
                ],
                'article_content' => [
                    'input_type' => 'value',
                    'display_name' => __('backend.內容'),
                ],
                'member_only' => [
                    'input_type' => 'value',
                    'display_name' => __('backend.僅供會員檢視'),
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
                $component_datas['dropdown_items']['items']['編輯'] = ['url' => url($this->uri . 'update?article_id=' . $article->id)];
                if (auth()->user()->hasAccess(['delete-' . $this->permission_controller_string])) {
                    $component_datas['dropdown_items']['items']['刪除'] = ['url' => url($this->uri . 'delete?article_id=' . $article->id)];
                }
            }
            if (auth()->user()->hasAccess(['create-' . $this->permission_controller_string])) {
                $component_datas['dropdown_items']['items']['複製'] = ['url' => url($this->uri . 'duplicate?article_id=' . $article->id)];
            }
            $component_datas['dropdown_items']['items']['預覽'] = ['url' => url(config('backend.article.preview_url', 'detail/') . $article->id)];
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
        if ($this->article_id) {
            $version_id = request('version_id', 0);
            if ($version_id) {
                $this->article_repository->applyVersion($this->article_id, $version_id);
            }
        }
        return redirect($this->uri . 'detail?article_id=' . $this->role_id);
    }

    /**
     * 刪除
     */
    public function delete()
    {
        if ($this->article_id) {
            $this->article_repository->delete($this->article_id);
        }
        return redirect($this->uri . 'index');
    }

    /**
     * 匯入文章
     */
    public function import()
    {
        $this->tpl_data['page_title'] = '匯入';

        // 匯入結果訊息
        $this->tpl_data['import_message'] = false;
        if (Cache::has('import_message')) {
            $this->tpl_data['import_message'] = Cache::get('import_message');
            Cache::forget('import_message');
        }
        return view($this->view_path . 'import', $this->tpl_data);
    }

    /**
     * 匯入文章
     */
    public function putImport()
    {
        // 匯入時間
        $created_at = date('Y-m-d H:i:s', time());

        // 匯入訊息
        $import_message = [];

        // 上傳檔案
        $prefix = 'product-import-' . date('Y-m-d');
        $res = FileService::upload('file_name', $prefix);

        // 檢查是否多檔上傳
        if (isset($res[0]['file_name'])) {
            set_time_limit(120);
            foreach ($res as $value) {

                // 開啟檔案
                $file = fopen(config('frontend.upload_path'). '/' . $value['file_name'], 'r');
                $key = 0;
                while(!feof($file)) {
                    $csv_value = fgetcsv($file);

                    // 略過前兩列資料
                    if ($key >= 2) {
                        $文章分類 = isset($csv_value[0]) ? $csv_value[0] : '';
                        $文章標題 = isset($csv_value[1]) ? $csv_value[1] : '';
                        $文章內容 = isset($csv_value[2]) ? $csv_value[2] : '';
                        $代表圖 = isset($csv_value[3]) ? $csv_value[3] : '';
                        $簡短說明 = isset($csv_value[4]) ? $csv_value[4] : '';
                        $管理者備註 = isset($csv_value[5]) ? $csv_value[5] : '';

                        // 檢查文章標題
                        $article_exist = false;
                        if (!blank($文章標題)) {
                            $article_query = $this->article_repository->model->where('title', $文章標題)->first();
                            if (!is_null($article_query)) {
                                $article_exist = true;
                            }
                        }

                        // 必填欄位檢查
                        if (!empty($文章標題) && !$article_exist) {

                            // 執行匯入
                            $article_datas['sort'] = $key - 1;
                            $article_datas['status'] = '啟用';
                            $article_datas['note'] = $管理者備註;
                            $article_datas['post_at'] = date('Y-m-d');
                            $article_datas['file_name'] = $代表圖;
                            $article_datas['title'] = $文章標題;
                            $article_datas['content'] = $文章內容;
                            $article_datas['summary'] = $簡短說明;
                            $article = $this->article_repository->model->create($article_datas);

                            // 處理複選分類資料
                            $category_array = explode(',', $文章分類);
                            $category_id_array = [];
                            if (count($category_array)) {
                                foreach ($category_array as $category_name) {
                                    $category_query = $this->article_category_repository->model->where('name', $category_name)->first();

                                    // 建立不存在的分類
                                    if (is_null($category_query)) {
                                        $category_id_array[] = $this->article_category_repository->update(0, ['name' => $category_name]);
                                    } else {
                                        $category_id_array[] = $category_query->id;
                                    }
                                }
                                if (count($category_id_array)) {
                                    $article->article_category()->sync($category_id_array);
                                }
                            }
                            $import_message[] = '檔案 ' . $value['origin_name'] . '第' . $key . '列資料匯入完成';
                        } else {
                            $massage_str = '';
                            if ($article_exist) {
                                $massage_str .= '已有相同的文章標題，';
                            }
                            if (empty($文章標題)) {
                                $massage_str .= '文章標題為必填資料，';
                            }
                            $import_message[] = '檔案 ' . $value['origin_name'] . '第' . $key . '列資料匯入失敗，' . $massage_str;
                        }
                    }
                    $key++;
                }
                fclose($file);
            }
        } else {
            $import_message[] = '檔案 ' . $value['origin_name'] . '上傳失敗';
        }

        // 將匯入訊息存入快取
        if (count($import_message)) {
            Cache::put('import_message', $import_message, 30);
        }
        return redirect($this->uri . 'import');
    }
}
