<?php

namespace Onepoint\Base\Controllers;

use App\Http\Controllers\Controller;
use Cache;
use Onepoint\Base\Repositories\ArticleAttachmentRepository;
use Onepoint\Base\Repositories\ArticleCategoryRepository;
use Onepoint\Base\Repositories\ArticleImageRepository;
use Onepoint\Base\Repositories\ArticleRepository;
use Onepoint\Dashboard\Services\FileService;
use Onepoint\Dashboard\Traits\ShareMethod;

/**
 * 文章
 */
class ArticleController extends Controller
{
    use ShareMethod;

    /**
     * 建構子
     */
    public function __construct(ArticleRepository $article_repository, ArticleCategoryRepository $article_category_repository)
    {
        $this->share();
        $this->article_repository = $article_repository;
        $this->article_category_repository = $article_category_repository;

        // 預設網址
        $this->uri = config('dashboard.uri') . '/article/';
        $this->tpl_data['uri'] = $this->uri;

        // view 路徑
        $this->view_path = 'base::' . config('dashboard.view_path') . '.pages.article.';
        $this->article_id = request('article_id', false);
        $this->tpl_data['article_id'] = $this->article_id;
    }

    /**
     * 列表
     */
    public function index()
    {
        // 列表基本設定資料
        $component_datas = $this->listPrepare(get_class($this), 'article_id');

        // 表格欄位設定
        $component_datas['th'] = [
            ['title' => __('dashboard::backend.標題')],
            ['title' => __('dashboard::backend.分類')],
            ['title' => __('base::article.文章網址')],
        ];
        $component_datas['column'] = [
            // ['type' => 'badges', 'column_name' => 'article_title', 'set_value' => [
            //     'click' => ['class' => 'badge badge-secondary', 'badge_title' => '點擊:'],
            // ]],
            ['type' => 'function', 'function_name' => 'Onepoint\Base\Controllers\ArticleController@articleTitleShow'],
            ['type' => 'belongsToMany', 'with' => 'article_category', 'column_name' => 'category_name'],
            ['type' => 'url', 'url' => url(config('article.preview_url', '')), 'slash' => ['article_title_slug']],
        ];

        // 文章分類選單
        $article_category_repository = new ArticleCategoryRepository;
        $this->tpl_data['category_select_item'] = $article_category_repository->getOptionItem();

        // 列表資料查詢
        $component_datas['list'] = $this->article_repository->getList($this->article_id, config('backend.paginate'));
        $component_datas['use_drag_rearrange'] = true;
        $component_datas['use_sort'] = false;
        $component_datas['detail_hide'] = false;
        $component_datas['use_duplicate'] = true;
        $this->tpl_data['component_datas'] = $component_datas;
        return view($this->view_path . 'index', $this->tpl_data);
    }

    /**
     * 標題及關聯數量顯示
     */
    public static function articleTitleShow($element)
    {
        $str = $element->article_title;
        $str .= '<br><span class="badge badge-primary"><i class="fas fa-images"></i> 圖片：' . $element->image_count . '</span>';
        $str .= '<span class="ml-1 badge badge-primary"><i class="fas fa-paperclip"></i> 附檔：' . $element->attachment_count . '</span>';
        $str .= '<span class="ml-1 badge badge-secondary"><i class="fas fa-book-reader"></i> 點擊：' . $element->click . '</span>';
        return $str;
    }

    /**
     * 批次處理
     */
    public function putIndex()
    {
        // $settings['file_field'] = 'file_name';
        // $settings['folder'] = 'article';
        // $settings['image_scale'] = true;
        $settings['use_version'] = true;
        return $this->batch($this->article_repository, $settings);
    }

    /**
     * 編輯
     */
    public function update()
    {
        $this->tpl_data['article'] = false;
        $this->tpl_data['tab'] = request('tab', 'normal');

        // 分類值陣列
        $category_id_array = [];
        if ($this->article_id) {
            $query = $this->article_repository->getOne($this->article_id);
            $this->tpl_data['article'] = $query;
            foreach ($query->article_category as $value) {
                $category_id_array[] = $value->id;
            }
        }

        // 分類選單資料
        $category_select_item = $this->article_category_repository->getOptionItem();

        // 一般表單資料
        $this->tpl_data['form_array_normal'] = [
            'article_title' => [
                'input_type' => 'text',
                'display_name' => __('dashboard::backend.標題'),
            ],
            'article_category_id[]' => [
                'input_type' => 'select',
                'display_name' => __('dashboard::backend.分類'),
                'input_value' => $category_id_array,
                'option' => $category_select_item,
                'attribute' => ['multiple' => 'multiple', 'size' => 5],
                'help' => __('dashboard::backend.按著Ctrl點選可複選多個項目'),
            ],
            'article_content' => [
                'input_type' => 'tinymce',
                'display_name' => __('dashboard::backend.內容'),
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
        $this->tpl_data['component_datas']['back_url'] = false;
        $this->tpl_data['component_datas']['footer_hide'] = true;
        if ($this->article_id && !isset($this->tpl_data['duplicate'])) {
            return view($this->view_path . 'update', $this->tpl_data);
        }
        return view($this->view_path . 'add', $this->tpl_data);
    }

    /**
     * 送出編輯資料
     */
    public function putUpdate()
    {
        $tab = request('tab', 'normal');
        $res = $this->article_repository->setUpdate($this->article_id);
        if ($res) {
            session()->flash('notify.message', __('dashboard::backend.資料編輯完成'));
            session()->flash('notify.type', 'success');
            if ($this->article_id) {
                return redirect($this->uri . 'update?' . $this->base_service->getQueryString(true, true) . '&tab=' . $tab);
            } else {
                return redirect($this->uri . 'update?article_id=' . $res . '&' . $this->base_service->getQueryString(true, true) . '&tab=' . $tab);
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
        $this->article_id = 0;
        return $this->putUpdate();
    }

    /**
     * 細節
     */
    public function detail()
    {
        if ($this->article_id) {

            // 細節基本設定資料
            $article = $this->article_repository->getOne($this->article_id);
            $this->tpl_data['article'] = $article;
            $component_datas = $this->detailPrepare(get_class($this), 'article_id', $article);

            // 表單資料
            $category_value_str = $article->article_category->implode('category_name', ', ');
            $this->tpl_data['form_array'] = [
                'article_title' => [
                    'input_type' => 'value',
                    'display_name' => __('dashboard::backend.標題'),
                ],
                'article_category_id' => [
                    'input_type' => 'value',
                    'display_name' => __('dashboard::backend.分類'),
                    'input_value' => $category_value_str,
                ],
                'post_at' => [
                    'input_type' => 'value',
                    'display_name' => __('dashboard::backend.發佈日期'),
                ],
                'article_content' => [
                    'input_type' => 'value',
                    'display_name' => __('dashboard::backend.內容'),
                ],
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
        return redirect($this->uri . 'detail?' . $this->base_service->getQueryString(true, true));
    }

    /**
     * 刪除
     */
    public function delete()
    {
        if ($this->article_id) {
            $this->article_repository->delete($this->article_id);
        }
        return redirect($this->uri . 'detail?' . $this->base_service->getQueryString(true, true));
    }

    /**
     * 修改排序
     */
    public function rearrange()
    {
        if ($this->article_id) {
            $this->article_repository->rearrange();
        }
        return redirect($this->uri . 'detail?' . $this->base_service->getQueryString(true, true));
    }

    /**
     * 拖曳排序
     */
    public function dragSort()
    {
        return $this->article_repository->dragRearrange();
    }

    /**
     * 刪除圖片
     */
    public function deleteImage($image_id)
    {
        if ($this->article_id) {
            $article_image_repository = new ArticleImageRepository;
            $article_image_repository->model->find($image_id)->delete();
        }
        return redirect($this->uri . 'update?article_id=' . $this->article_id . '&tab=image');
    }

    /**
     * 圖檔批次上傳
     */
    public function multiple()
    {
        $this->tpl_data['page_title'] = '圖檔批次上傳';
        return view($this->view_path . 'multiple', $this->tpl_data);
    }

    /**
     * 圖檔批次上傳
     */
    public function postMultiple()
    {
        $arr = ['success' => false, "error" => 'product id error'];
        $article = $this->article_repository->model->find($this->article_id);
        if (!is_null($article)) {
            $qquuid = request('qquuid', '');
            $qqfilename = request('qqfilename', '');
            if (!empty($qquuid)) {
                $article_image_repository = new ArticleImageRepository;
                $article_image_repository->upload_file_form_name = 'qqfile';
                $article_image_repository->upload_file_column_name = 'file_name';
                $article_image_repository->upload_file_name_prefix = 'article';
                $article_image_repository->upload_file_folder = 'article';
                $article_image_repository->upload_file_resize = true;
                $result = $article_image_repository->append(['image_title' => $article->article_title])->update();
                $arr = ['success' => true, "uuid" => $qquuid, 'uploadName' => $qqfilename];
            } else {
                $arr = ['success' => false, "error" => 'uuid error'];
            }
        } else {
            $arr = ['success' => false, "error" => 'material id error'];
        }
        return response()->json($arr);
    }

    /**
     * 記錄圖片排序
     */
    public function imageSort()
    {
        $idArray = explode(",", request('ids', ''));
        if (count($idArray)) {
            $article_image_repository = new ArticleImageRepository;
            $count = 1;
            foreach ($idArray as $id) {
                $article_image_repository->model->find($id)->update(['sort' => $count]);
                $count++;
            }
        }
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

    /**
     * 編輯附檔
     */
    public function attachmentUpdate($attachment_id)
    {
        if ($this->article_id) {
            $page_title = __('base::article.編輯文章附檔');

            $article_attachment_repository = new ArticleAttachmentRepository;
            $query = $article_attachment_repository->getOne($attachment_id);
            $this->tpl_data['attachment'] = $query;

            // 一般表單資料
            $this->tpl_data['form_array_normal'] = [
                'file_name' => [
                    'input_type' => 'file',
                    'display_name' => __('base::article.文章附檔'),
                    'upload_path' => 'article',
                    'value_type' => 'file',
                    // 'image_attribute' => ['style' => 'width:200px;'],
                    // 'image_thumb' => true,
                    'multiple' => false,
                    'help' => __('dashboard::backend.檔案上傳說明', ['max_size' => ini_get('upload_max_filesize')]),
                ],
                'origin_name' => [
                    'input_type' => 'text',
                    'display_name' => __('base::article.附檔檔名'),
                ],
                'attachment_title' => [
                    'input_type' => 'text',
                    'display_name' => __('base::article.附檔標題'),
                ],
                'attachment_description' => [
                    'input_type' => 'textarea',
                    'display_name' => __('base::article.附檔說明'),
                    'rows' => 5,
                ],
                'note' => [
                    'input_type' => 'textarea',
                    'display_name' => __('dashboard::backend.備註'),
                    'rows' => 5,
                ],
            ];

            // 樣版資料
            $component_datas['page_title'] = $page_title;
            $component_datas['back_url'] = false;
            $this->tpl_data['component_datas'] = $component_datas;
            return view($this->view_path . 'attachment-update', $this->tpl_data);
        }
    }

    /**
     * 接收附檔
     */
    public function putAttachmentUpdate(ArticleAttachmentRepository $article_attachment_repository, $attachment_id)
    {
        if ($this->article_id) {
            $res = $article_attachment_repository->setUpdate($attachment_id);
            if ($res) {
                session()->flash('notify.message', __('dashboard::backend.資料編輯完成'));
                session()->flash('notify.type', 'success');
            } else {
                session()->flash('notify.message', __('dashboard::backend.資料編輯失敗'));
                session()->flash('notify.type', 'danger');
            }
        }
        return redirect($this->uri . 'update?' . $this->base_service->getQueryString(true, true) . '&tab=attachment');
    }

    /**
     * 新增附檔
     */
    public function attachmentAdd()
    {
        $page_title = __('base::article.新增文章附檔');

        // 一般表單資料
        $this->tpl_data['form_array_normal'] = [
            'file_name' => [
                'input_type' => 'file',
                'display_name' => __('base::article.文章附檔'),
                'upload_path' => 'article',
                'value_type' => 'file',
                // 'image_attribute' => ['style' => 'width:200px;'],
                // 'image_thumb' => true,
                'multiple' => false,
                'help' => __('dashboard::backend.檔案上傳說明', ['max_size' => ini_get('upload_max_filesize')]),
            ],
            'attachment_title' => [
                'input_type' => 'text',
                'display_name' => __('base::article.附檔標題'),
            ],
            'attachment_description' => [
                'input_type' => 'textarea',
                'display_name' => __('base::article.附檔說明'),
                'rows' => 5,
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
        return view($this->view_path . 'attachment-add', $this->tpl_data);
    }

    /**
     * 接收新增附檔
     */
    public function putAttachmentAdd(ArticleAttachmentRepository $article_attachment_repository)
    {
        $res = $article_attachment_repository->setUpdate();
        if ($res) {
            session()->flash('notify.message', __('dashboard::backend.資料編輯完成'));
            session()->flash('notify.type', 'success');
        } else {
            session()->flash('notify.message', __('dashboard::backend.資料編輯失敗'));
            session()->flash('notify.type', 'danger');
        }
        return redirect($this->uri . 'update?' . $this->base_service->getQueryString(true, true) . '&tab=attachment');
    }

    /**
     * 刪除附檔
     */
    public function attachmentDelete(ArticleAttachmentRepository $article_attachment_repository, $attachment_id)
    {
        if ($this->article_id) {
            $article_attachment_repository->model->find($attachment_id)->delete();
            return redirect($this->uri . 'update?' . $this->base_service->getQueryString(true, true) . '&tab=attachment');
        }
    }

    /**
     * 下載附檔
     */
    public function attachmentDownload(ArticleAttachmentRepository $article_attachment_repository, $attachment_id)
    {
        $query_article_attachment = $article_attachment_repository->model->find($attachment_id);
        // $storage_path = storage_path(config('frontend.upload_path') . '/article/' . $query_article_attachment->file_name);
        return FileService::download($query_article_attachment->file_name, $query_article_attachment->origin_name . '.' . $query_article_attachment->file_extention, 'article');
    }
}
