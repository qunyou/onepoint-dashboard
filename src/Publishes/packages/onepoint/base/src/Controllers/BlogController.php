<?php

namespace Onepoint\Base\Controllers;

use App\Http\Controllers\Controller;
use Cache;
use Onepoint\Base\Repositories\BlogCategoryRepository;
use Onepoint\Base\Repositories\BlogRepository;
use Onepoint\Dashboard\Services\BaseService;
use Onepoint\Dashboard\Services\FileService;
use Onepoint\Dashboard\Services\ImageService;
use Onepoint\Dashboard\Traits\ShareMethod;

/**
 * 部落格
 */
class BlogController extends Controller
{
    use ShareMethod;

    /**
     * 建構子
     */
    public function __construct(BaseService $base_service, BlogRepository $blog_repository, BlogCategoryRepository $blog_category_repository)
    {
        $this->share();
        $this->blog_repository = $blog_repository;
        $this->blog_category_repository = $blog_category_repository;

        // 預設網址
        $this->uri = config('dashboard.uri') . '/blog/';
        $this->tpl_data['uri'] = $this->uri;

        // view 路徑
        $this->view_path = 'base::' . config('dashboard.view_path') . '.pages.blog.';
        $this->blog_id = request('blog_id', false);
        $this->tpl_data['blog_id'] = $this->blog_id;
    }

    /**
     * 列表
     */
    public function index()
    {
        // 選圈處理slug
        // $blog_repository = new BlogRepository;
        // $blog_query = $blog_repository->model->all();
        // foreach ($blog_query as $blog) {
        //     $blog->blog_title_slug = BaseService::slug($blog->blog_title, '-');
        //     $blog->save();
        // }

        // 列表標題
        // if (!$this->tpl_data['trashed']) {
        //     $this->tpl_data['component_datas']['page_title'] = __('base::blog.文章列表');
        // } else {
        //     $this->tpl_data['component_datas']['page_title'] = __('dashboard::backend.資源回收');
        // }
        $component_datas = $this->listPrepare();
        $permission_controller_string = get_class($this);
        $component_datas['permission_controller_string'] = $permission_controller_string;
        $component_datas['uri'] = $this->uri;
        $component_datas['back_url'] = url($this->uri . 'index');

        // 主資料 id query string 字串
        $component_datas['id_string'] = 'blog_id';

        // 表格欄位設定
        $component_datas['th'] = [
            ['title' => __('base::blog.文章標題')],
            ['title' => __('base::blog.部落格分類')],
            ['title' => __('base::blog.文章網址')],
            ['title' => __('base::blog.發佈日期')],
        ];
        // 'click' => ['class' => 'badge badge-secondary', 'badge_title' => '點擊:']
        $component_datas['column'] = [
            ['type' => 'badges', 'column_name' => 'blog_title', 'set_value' => [
                'click' => ['class' => 'badge badge-secondary', 'badge_title' => '點擊:'],
            ]],
            ['type' => 'belongsToMany', 'with' => 'blog_category', 'column_name' => 'category_name'],
            ['type' => 'url', 'url' => url(config('blog.preview_url')), 'slash' => ['blog_title_slug']],
            ['type' => 'column', 'column_name' => 'post_at'],
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
        // if (auth()->user()->hasAccess(['update-' . $this->permission_controller_string])) {
        //     $component_datas['dropdown_items']['items']['舊版本'] = ['url' => url($this->uri . 'index?version=true')];
        // }
        // if (auth()->user()->hasAccess(['delete-' . $this->permission_controller_string])) {
        //     $component_datas['dropdown_items']['items']['資源回收'] = ['url' => url($this->uri . 'index?trashed=true')];
        // }

        // 列表資料查詢
        $component_datas['list'] = $this->blog_repository->getList($this->blog_id, config('backend.paginate'));
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
        $settings['folder'] = 'blog';
        $settings['image_scale'] = true;
        $settings['use_version'] = true;
        $result = $this->blog_repository->batch($settings);
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
        $this->tpl_data['blog'] = false;

        // 分類值陣列
        $category_id_array = [];
        if ($this->blog_id) {
            $page_title = __('base::blog.文章編輯');
            $query = $this->blog_repository->getOne($this->blog_id);

            // 複製時不顯示圖片
            if (isset($this->tpl_data['duplicate']) && $this->tpl_data['duplicate']) {
                $query->file_name = '';
                $page_title = __('dashboard::backend.複製');
            }
            $this->tpl_data['blog'] = $query;

            // 刪除附檔
            $delete_file = request('delete_file', false);
            if ($delete_file) {
                ImageService::delete($query->file_name, true, 'blog');
                $query->file_name = '';
                $query->save();
            }
            foreach ($query->blog_category as $value) {
                $category_id_array[] = $value->id;
            }
        } else {
            $page_title = __('base::blog.新增文章');
        }

        // 分類選單資料
        $category_select_item = $this->blog_category_repository->getOptionItem();

        // 一般表單資料
        $this->tpl_data['form_array_normal'] = [
            'blog_title' => [
                'input_type' => 'text',
                'display_name' => __('base::blog.文章標題'),
            ],
            'blog_category_id[]' => [
                'input_type' => 'select',
                'display_name' => __('base::blog.文章分類'),
                'input_value' => $category_id_array,
                'option' => $category_select_item,
                'attribute' => ['multiple' => 'multiple', 'size' => 5],
                'help' => '按著Ctrl點選，可複選多個項目',
            ],
            // 'file_name' => [
            //     'input_type' => 'file',
            //     'display_name' => __('dashboard::backend.圖片'),
            //     'upload_path' => 'blog',
            //     'value_type' => 'image',
            //     'image_attribute' => ['style' => 'width:200px;'],
            //     'image_thumb' => false,
            //     'multiple' => false,
            //     'help' => __('dashboard::backend.圖片上傳說明', ['max_size' => ini_get('upload_max_filesize')]),
            // ],
            // 'summary' => [
            //     'input_type' => 'text',
            //     'display_name' => __('dashboard::backend.列表摘要'),
            // ],
            // 'post_at' => [
            //     'input_type' => 'date',
            //     'display_name' => __('dashboard::backend.發佈日期'),
            // ],
            'blog_content' => [
                'input_type' => 'tinymce',
                'display_name' => __('base::blog.文章內容'),
            ],
        ];

        // 進階表單資料
        $this->tpl_data['form_array_advanced'] = [
            'sort' => [
                'input_type' => 'number',
                'display_name' => __('dashboard::backend.排序'),
            ],
            'status' => [
                'input_type' => 'select',
                'display_name' => __('dashboard::backend.狀態'),
                'option' => config('backend.status_item'),
            ],
            // 'member_only' => [
            //     'input_type' => 'select',
            //     'display_name' => __('dashboard::backend.僅供會員檢視'),
            //     'option' => config('backend.status_item'),
            // ],
            // 'url' => [
            //     'input_type' => 'text',
            //     'display_name' => __('dashboard::backend.外連網址'),
            // ],
            'note' => [
                'input_type' => 'textarea',
                'display_name' => __('dashboard::backend.備註'),
                'rows' => 5,
            ],
        ];

        // 社群及SEO
        $this->tpl_data['form_array_seo'] = [
            'html_title' => [
                'input_type' => 'text',
                'display_name' => __('dashboard::backend.網頁標題'),
            ],
            'meta_keywords' => [
                'input_type' => 'text',
                'display_name' => __('dashboard::backend.關鍵字'),
            ],
            'meta_description' => [
                'input_type' => 'text',
                'display_name' => __('dashboard::backend.網頁敘述'),
            ],
        ];

        // 樣版資料
        $component_datas['page_title'] = $page_title;
        $component_datas['back_url'] = false;
        $this->tpl_data['component_datas'] = $component_datas;
        return view($this->view_path . 'update', $this->tpl_data);
    }

    /**
     * 送出編輯資料
     */
    public function putUpdate()
    {
        $res = $this->blog_repository->setUpdate($this->blog_id);
        if ($res) {
            session()->flash('notify.message', __('dashboard::backend.資料編輯完成'));
            session()->flash('notify.type', 'success');
            if ($this->blog_id) {
                return redirect($this->uri . 'update?' . $this->base_service->getQueryString(true, true));
            } else {
                return redirect($this->uri . 'update?blog_id=' . $res . '&' . $this->base_service->getQueryString(true, true));
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
        $this->blog_id = 0;
        return $this->putUpdate();
    }

    /**
     * 細節
     */
    public function detail()
    {
        if ($this->blog_id) {
            $blog = $this->blog_repository->getOne($this->blog_id);
            $this->tpl_data['blog'] = $blog;
            $category_value_str = $blog->blog_category->implode('category_name', ', ');

            // 表單資料
            $this->tpl_data['form_array'] = [
                'blog_title' => [
                    'input_type' => 'value',
                    'display_name' => __('dashboard::backend.標題'),
                ],
                'blog_category_id' => [
                    'input_type' => 'value',
                    'display_name' => __('dashboard::backend.分類'),
                    'input_value' => $category_value_str,
                ],
                // 'file_name' => [
                //     'input_type' => 'value',
                //     'display_name' => __('dashboard::backend.圖片'),
                //     'upload_path' => 'blog',
                //     'value_type' => 'image',
                //     'image_attribute' => ['style' => 'width:200px;'],
                // ],
                // 'summary' => [
                //     'input_type' => 'value',
                //     'display_name' => __('dashboard::backend.列表摘要'),
                // ],
                // 'post_at' => [
                //     'input_type' => 'value',
                //     'display_name' => __('dashboard::backend.發佈日期'),
                // ],
                'blog_content' => [
                    'input_type' => 'value',
                    'display_name' => __('dashboard::backend.內容'),
                ],
                // 'member_only' => [
                //     'input_type' => 'value',
                //     'display_name' => __('dashboard::backend.僅供會員檢視'),
                // ],
                'html_title' => [
                    'input_type' => 'value',
                    'display_name' => __('dashboard::backend.網頁標題'),
                ],
                'meta_keywords' => [
                    'input_type' => 'value',
                    'display_name' => __('dashboard::backend.關鍵字'),
                ],
                'meta_description' => [
                    'input_type' => 'value',
                    'display_name' => __('dashboard::backend.網頁敘述'),
                ],
                'click' => [
                    'input_type' => 'value',
                    'display_name' => __('dashboard::backend.點擊數'),
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
            $component_datas['page_title'] = __('dashboard::backend.檢視');
            // $component_datas['back_url'] = url($this->uri . 'index?page=' . session('page', 1));
            $component_datas['back_url'] = false;
            $component_datas['dropdown_items']['btn_align'] = 'float-left';
            if (auth()->user()->hasAccess(['update-' . $this->permission_controller_string])) {
                $component_datas['dropdown_items']['items']['編輯'] = ['url' => url($this->uri . 'update?blog=' . $blog->id)];
                if (auth()->user()->hasAccess(['delete-' . $this->permission_controller_string])) {
                    $component_datas['dropdown_items']['items']['刪除'] = ['url' => url($this->uri . 'delete?blog=' . $blog->id)];
                }
            }
            if (auth()->user()->hasAccess(['create-' . $this->permission_controller_string])) {
                $component_datas['dropdown_items']['items']['複製'] = ['url' => url($this->uri . 'duplicate?blog=' . $blog->id)];
            }
            if (config('backend.blog.preview_url', false)) {
                $component_datas['dropdown_items']['items']['預覽'] = ['url' => url(config('backend.blog.preview_url') . '/' . $blog->id)];
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
        if ($this->blog_id) {
            $version_id = request('version_id', 0);
            if ($version_id) {
                $this->blog_repository->applyVersion($this->blog_id, $version_id);
            }
        }
        return redirect($this->uri . 'detail?' . $this->base_service->getQueryString(true, true));
    }

    /**
     * 刪除
     */
    public function delete()
    {
        if ($this->blog_id) {
            $this->blog_repository->delete($this->blog_id);
        }
        return redirect($this->uri . 'detail?' . $this->base_service->getQueryString(true, true));
    }

    /**
     * 修改排序
     */
    public function rearrange()
    {
        if ($this->blog_id) {
            $this->blog_repository->rearrange();
        }
        return redirect($this->uri . 'detail?' . $this->base_service->getQueryString(true, true));
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
                $file = fopen(config('frontend.upload_path') . '/' . $value['file_name'], 'r');
                $key = 0;
                while (!feof($file)) {
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
                        $blog_exist = false;
                        if (!blank($文章標題)) {
                            $blog_query = $this->blog_repository->model->where('title', $文章標題)->first();
                            if (!is_null($blog_query)) {
                                $blog_exist = true;
                            }
                        }

                        // 必填欄位檢查
                        if (!empty($文章標題) && !$blog_exist) {

                            // 執行匯入
                            $blog_datas['sort'] = $key - 1;
                            $blog_datas['status'] = '啟用';
                            $blog_datas['note'] = $管理者備註;
                            $blog_datas['post_at'] = date('Y-m-d');
                            $blog_datas['file_name'] = $代表圖;
                            $blog_datas['title'] = $文章標題;
                            $blog_datas['content'] = $文章內容;
                            $blog_datas['summary'] = $簡短說明;
                            $blog = $this->blog_repository->model->create($blog_datas);

                            // 處理複選分類資料
                            $category_array = explode(',', $文章分類);
                            $category_id_array = [];
                            if (count($category_array)) {
                                foreach ($category_array as $category_name) {
                                    $category_query = $this->blog_category_repository->model->where('name', $category_name)->first();

                                    // 建立不存在的分類
                                    if (is_null($category_query)) {
                                        $category_id_array[] = $this->blog_category_repository->update(0, ['name' => $category_name]);
                                    } else {
                                        $category_id_array[] = $category_query->id;
                                    }
                                }
                                if (count($category_id_array)) {
                                    $blog->blog_category()->sync($category_id_array);
                                }
                            }
                            $import_message[] = '檔案 ' . $value['origin_name'] . '第' . $key . '列資料匯入完成';
                        } else {
                            $massage_str = '';
                            if ($blog_exist) {
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