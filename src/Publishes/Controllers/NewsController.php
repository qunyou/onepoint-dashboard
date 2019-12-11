<?php
namespace App\Http\Controllers;

use Cache;
use Onepoint\Dashboard\Services\BaseService;
use Onepoint\Dashboard\Services\ImageService;
use Onepoint\Dashboard\Services\FileService;
use App\Repositories\NewsRepository;
use App\Repositories\NewsCategoryRepository;
use App\Entities\NewCategory;
use App\Entities\News;

/**
 * 最新消息
 */
class NewsController extends Controller
{
    /**
     * 建構子
     */
    function __construct(BaseService $base_services, NewsRepository $news_repository, NewsCategoryRepository $news_category_repository)
    {
        $this->base_services = $base_services;
        $this->tpl_data = $base_services->tpl_data;
        $this->tpl_data['base_services'] = $this->base_services;
        $this->news_repository = $news_repository;
        $this->news_category_repository = $news_category_repository;
        $this->permission_controller_string = class_basename(get_class($this));
        $this->tpl_data['permission_controller_string'] = $this->permission_controller_string;

        // 預設網址
        $this->uri = config('dashboard.uri') . '/news/';
        $this->tpl_data['uri'] = $this->uri;

        // view 路徑
        $this->view_path = config('dashboard.view_path') . '.pages.news.';

        // 主功能標題
        $this->tpl_data['page_header'] = __('news.新聞管理');
        $this->news_id = request('news_id', false);
        $this->tpl_data['news_id'] = $this->news_id;
    }

    /**
     * 列表
     */
    public function index(ImageService $image_service)
    {
        $this->tpl_data['image_service'] = $image_service;
        $this->tpl_data['list'] = $this->news_repository->getList($this->news_id, config('backend.paginate'));

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
        $settings['folder'] = 'news';
        $settings['image_scale'] = true;
        $settings['use_version'] = true;
        $result = $this->news_repository->batch($settings);
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
        $this->tpl_data['news'] = false;
        
        // 分類值陣列
        $category_id_array = [];
        if ($this->news_id) {
            $page_title = __('backend.編輯');
            $query = $this->news_repository->getOne($this->news_id);

            // 複製時不顯示圖片
            if (isset($this->tpl_data['duplicate']) && $this->tpl_data['duplicate']) {
                $query->file_name = '';
                $page_title = __('backend.複製');
            }
            $this->tpl_data['news'] = $query;

            // 刪除附檔
            $delete_file = request('delete_file', false);
            if ($delete_file) {
                ImageService::delete($query->file_name, true, 'news');
                $query->file_name = '';
                $query->save();
                // return back();
            }
            foreach ($query->news_category as $value) {
                $category_id_array[] = $value->id;
            }
        } else {
            $page_title = __('backend.新增');
        }

        // 分類選單資料
        $category_select_item = $this->news_category_repository->getOptionItem();

        // 一般表單資料
        $this->tpl_data['form_array_normal'] = [
            'news_title' => [
                'input_type' => 'text',
                'display_name' => __('backend.標題'),
            ],
            'news_category_id[]' => [
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
                'upload_path' => 'news',
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
            'news_content' => [
                'input_type' => 'tinymce',
                'display_name' => __('backend.內容'),
            ],
        ];

        // 進階表單資料
        $this->tpl_data['form_array_advanced'] = [
            'custom_list_thumb' => [
                'input_type' => 'textarea',
                'display_name' => __('news.自訂列表縮圖內容'),
                'rows' => 10,
            ],
            'public_forever' => [
                'input_type' => 'radio',
                'display_name' => __('backend.永久發佈'),
                'option' => config('backend.status_item'),
                'help' => __('backend.發佈期限說明'),
            ],
            'public_start_at' => [
                'input_type' => 'date',
                'display_name' => __('backend.開始日期'),
            ],
            'public_end_at' => [
                'input_type' => 'date',
                'display_name' => __('backend.結束日期'),
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
        $res = $this->news_repository->setUpdate($this->news_id);
        if ($res) {
            session()->flash('notify.message', __('backend.資料編輯完成'));
            session()->flash('notify.type', 'success');
            return redirect($this->uri . 'detail?news_id=' . $res);
        } else {
            session()->flash('notify.message', __('backend.資料編輯失敗'));
            session()->flash('notify.type', 'danger');
            return redirect($this->uri . 'update?news_id=' . $this->news_id)->withInput();
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
        $this->news_id = 0;
        return $this->putUpdate();
    }

    /**
     * 細節
     */
    public function detail()
    {
        if ($this->news_id) {
            $news = $this->news_repository->getOne($this->news_id);
            $this->tpl_data['news'] = $news;
            $category_value_str = $news->news_category->implode('category_name', ', ');

            // 表單資料
            $this->tpl_data['form_array'] = [
                'news_title' => [
                    'input_type' => 'value',
                    'display_name' => __('backend.標題'),
                ],
                'news_category_id' => [
                    'input_type' => 'value',
                    'display_name' => __('backend.分類'),
                    'input_value' => $category_value_str
                ],
                'file_name' => [
                    'input_type' => 'value',
                    'display_name' => __('backend.圖片'),
                    'upload_path' => 'news',
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
                'news_content' => [
                    'input_type' => 'value',
                    'display_name' => __('backend.內容'),
                ],
                'custom_list_thumb' => [
                    'input_type' => 'value',
                    'display_name' => __('news.自訂列表縮圖內容'),
                ],
                'public_forever' => [
                    'input_type' => 'value',
                    'display_name' => __('backend.永久發佈'),
                ],
                'public_start_at' => [
                    'input_type' => 'value',
                    'display_name' => __('backend.開始日期'),
                ],
                'public_end_at' => [
                    'input_type' => 'value',
                    'display_name' => __('backend.結束日期'),
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
                $component_datas['dropdown_items']['items']['編輯'] = ['url' => url($this->uri . 'update?news_id=' . $news->id)];
                if (auth()->user()->hasAccess(['delete-' . $this->permission_controller_string])) {
                    $component_datas['dropdown_items']['items']['刪除'] = ['url' => url($this->uri . 'delete?news_id=' . $news->id)];
                }
            }
            if (auth()->user()->hasAccess(['create-' . $this->permission_controller_string])) {
                $component_datas['dropdown_items']['items']['複製'] = ['url' => url($this->uri . 'duplicate?news_id=' . $news->id)];
            }
            $component_datas['dropdown_items']['items']['預覽'] = ['url' => url(config('backend.news.preview_url', 'detail/') . $news->id)];
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
        if ($this->news_id) {
            $version_id = request('version_id', 0);
            if ($version_id) {
                $this->news_category_repository->applyVersion($this->news_id, $version_id);
            }
        }
        return redirect($this->uri . 'detail?news_id=' . $this->role_id);
    }

    /**
     * 刪除
     */
    public function delete()
    {
        if ($this->news_id) {
            $this->news_repository->delete($this->news_id);
        }
        return redirect($this->uri . 'index');
    }

    /**
     * 匯入
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
     * 匯入
     */
    public function putImport()
    {
        // 匯入時間
        $created_at = date('Y-m-d H:i:s', time());

        // 匯入訊息
        $import_message = [];

        // 上傳檔案
        $prefix = 'news-' . date('Y-m-d');
        $res = FileService::upload('file_name', $prefix, '', 'excel');

        // 檢查是否多檔上傳
        if (isset($res[0]['file_name'])) {
            set_time_limit (120);
            foreach ($res as $value) {

                // 開啟檔案
                $file = fopen(config('frontend.upload_path') . '/excel/' . $value['file_name'], 'r');
                $key = 1;
                while(!feof($file)) {
                    $csv_value = fgetcsv($file);

                    // 略過第一列資料
                    if ($key > 1) {
                        $分類 = isset($csv_value[0]) ? $csv_value[0] : '';
                        $標題 = isset($csv_value[1]) ? $csv_value[1] : '';

                        // 檢查必填資料
                        if (!empty($分類) && !empty($標題)) {
                            $列表摘要 = isset($csv_value[2]) ? $csv_value[2] : '';
                            $文章內容 = isset($csv_value[3]) ? $csv_value[3] : '';
                            $文章日期 = isset($csv_value[4]) ? $csv_value[4] : '';
                            $代表圖 = isset($csv_value[5]) ? $csv_value[5] : '';
                            $是否刪除 = isset($csv_value[6]) ? $csv_value[6] : '';

                            // 查詢分類id
                            $category_sync_array = [];
                            $category_arr = explode(',', $分類);
                            foreach ($category_arr as $item) {
                                if (!isset($category_id_array[$item])) {
                                    $dealer_category = NewCategory::where('name', $item)->first();
                                    if (!is_null($dealer_category)) {
                                        $category_id_array[$item] = $dealer_category->id;
                                    } else {

                                        // 自動建立資料
                                        $category_id_array[$item] = NewCategory::create([
                                            'status' => '啟用',
                                            'sort' => 0,
                                            'name' => $item
                                        ])->id;
                                        $import_message[] = '檔案 ' . $value['origin_name'] . '第' . $key . '列資料已自動建立分類，分類名稱『' . $item . '』';
                                    }
                                }
                                $category_sync_array[] = $category_id_array[$item];
                            }

                            // 設定寫入資料
                            unset($datas);
                            $datas['title'] = $標題;
                            $datas['summary'] = $列表摘要;
                            $datas['content'] = $文章內容;
                            $datas['file_name'] = $代表圖;

                            // 排序
                            $datas['sort'] = $key;

                            // 檢查資料是否存在
                            $check = News::where('title', $datas['title'])->first();

                            // 新增、更新或刪除資料
                            if (is_null($check)) {
                                if ($是否刪除 != 1) {
                                    $result = News::create($datas);
                                    $news_id = $result->id;

                                    // 處理複選資料
                                    $result->news_category()->sync($category_sync_array);
                                    // $import_message[] = '檔案 ' . $value['origin_name'] . '第'. $key . '列 - ' . $完整產品編碼 . '(' . $news_id . ')' . '：已建立';
                                } else {
                                    $import_message[] = '檔案 ' . $value['origin_name'] . '第'. $key . '列 - ' . $標題 . '：已設定刪除，未建立';
                                }
                            } else {
                                $news_id = $check->id;

                                // 刪除標記
                                if ($是否刪除 == 1) {
                                    $del_query = News::find($news_id);
                                    if (is_null($del_query)) {
                                        $import_message[] = '檔案 ' . $value['origin_name'] . '第' . $key . '列 - ' . $標題 . '(' . $news_id . ')' . '：設定刪除但找不到資料';
                                    } else {
                                        $del_query->delete();
                                        $import_message[] = '檔案 ' . $value['origin_name'] . '第' . $key . '列 - ' . $標題 . '(' . $news_id . ')' . '：已刪除';
                                    }
                                } else {

                                    // 更新資料
                                    News::find($news_id)->update($datas);

                                    // 處理複選資料
                                    $check->news_category()->sync($category_sync_array);
                                    $import_message[] = '檔案 ' . $value['origin_name'] . '第' . $key . '列 - ' . $標題 . '(' . $news_id . ')' . '：已更新';
                                }
                            }
                        } else {
                            $import_message[] = '檔案 ' . $value['origin_name'] . '第' . $key . '列資料匯入失敗，必填欄位無資料';
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
