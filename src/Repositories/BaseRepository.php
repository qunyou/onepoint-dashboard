<?php

namespace Onepoint\Dashboard\Repositories;

use Log;
use Illuminate\Support\Facades\Storage;
use Onepoint\Dashboard\Services\BaseService;
use Onepoint\Dashboard\Services\ImageService;
use Onepoint\Dashboard\Services\FileService;
// use DateTime;

/**
 * Repository基本可用方法
 */
class BaseRepository
{
    // 加入自訂值
    public $append_array = [];

    // 排除資料
    public $exclude_array = [];

    // 表單驗證
    public $rule_array = [];
    public $custom_name_array = [];

    // 上傳檔案檔名前綴
    public $upload_file_name_prefix;

    // 上傳表單名
    public $upload_file_form_name;

    // 自訂資料表欄位名稱(預設為 $upload_file_form_name)
    public $upload_file_column_name = '';

    // 上傳檔案大小限制
    public $upload_file_size_limit = 0;

    // 是否製作縮圖
    public $upload_file_resize = true;

    // 上傳資料夾
    public $upload_file_folder = '';

    // 上傳檔案大小欄位名
    public $upload_file_size_column_name;

    // 檔案標題欄位名稱
    public $upload_origin_file_column_name;

    // 檔案原始檔名欄位名稱
    public $upload_file_origin_column_name;

    // 檔案副檔名欄位名稱
    public $upload_file_extention_column_name;

    // 是否檢視刪除資料
    public $trashed = false;

    // 是否檢視備份資料
    public $old_version = false;

    // 是否使用版本功能
    public $use_version = true;

    // 除錯
    public $debug;

    /**
     * 建構子
     */
    function __construct($model)
    {
        $this->model = $model;
        $this->trashed = request('trashed', false);
        $this->old_version = request('version', false);
    }

    /**
     * 除錯模式
     */
    public function debug($debug = false)
    {
        $this->debug = $debug;
        return $this;
    }

    /**
     * 更新
     *
     * $id          int     自動編號，值為 0 時為新增資料
     * $manually    boolean 值為 true 時不接收表單資料，值全由 $datas 自訂
     *
     * return       int     更新或新增資料的自動編號
     */
    public function update($id = 0, $manually = false)
    {
        return $this->executeUpdate($id, $this->makeUpdateData($manually));
    }

    /**
     * 複製備份後更新
     *
     * $id          int     自動編號
     * $manually    boolean 值為 true 時不接收表單資料，值全由 $datas 自訂
     *
     * return       int     更新資料的自動編號
     */
    public function replicateUpdate($id, $manually = false)
    {
        $q = $this->model->find($id);
        $newVersion = $q->replicate();
        $newVersion->old_version = 1;
        $newVersion->origin_id = $id;
        $newVersion->save();
        $newVersion->delete();
        return $this->executeUpdate($id, $this->makeUpdateData($manually));
    }

    /**
     * 加入自訂值
     */
    public function append(array $datas)
    {
        $this->append_array = $datas;
        return $this;
    }
    
    /**
     * 排除資料
     */
    public function exclude(array $datas)
    {
        $this->exclude_array = $datas;
        return $this;
    }

    /**
     * 表單驗證
     * $datas           驗證規則陣列
     * $custom_name    自訂欄位名稱
     */
    public function rule(array $datas, array $custom_name)
    {
        $this->rule_array = $datas;
        $this->custom_name_array = $custom_name;
        return $this;
    }

    /**
     * 新增並啟用
     */
    public function updateAndActive($id = 0, $datas = [], $exclude = [], $manually = false, $rule = [])
    {
        $inputs = $this->makeUpdateData($datas, $exclude, $manually, $rule);

        // 只有新增的時候才啟用
        if (!$id) {
            $inputs['active'] = 1;
        }
        return $this->executeUpdate($id, $inputs);
    }

    /**
     * 產生更新資料
     */
    public function makeUpdateData($manually)
    {
        $inputs = [];
        if (!$manually) {
            $inputs = request()->all();
            unset($inputs['_token']);

            // 移除值為陣列的資料
            foreach ($inputs as $key => $value) {
                if (is_array($value)) {
                    unset($inputs[$key]);
                }
            }
        }

        // 排除資料
        if (count($this->exclude_array)) {
            foreach ($this->exclude_array as $value) {
                unset($inputs[$value]);
            }
        }

        // 驗證表單
        // 有錯誤會自動返回
        if (count($this->rule_array)) {
            request()->validate($this->rule_array, [], $this->custom_name_array);
        }

        // 加上自訂值
        if (count($this->append_array)) {
            foreach ($this->append_array as $key => $value) {
                $inputs[$key] = $value;
            }
        }
        return $inputs;
    }

    /**
     * 執行更新
     */
    public function executeUpdate($id, $datas)
    {
        if ($datas) {

            // 檔案上傳
            $res = $this->uploadFile($id);
            if ($res) {
                foreach ($res as $key => $value) {
                    $datas[$key] = $value;
                }
            }

            // 產生排序編號
            if (in_array('sort', $this->model->getFillable())) {
                if ((!$id && !isset($datas['sort'])) || (!$id && isset($datas['sort']) && is_null($datas['sort']))) {
                    $datas['sort'] = $this->model->count() + 1;
                }
                if ($id && !isset($datas['sort'])) {
                    $datas['sort'] = $this->model->count() + 1;
                }
                if ($id && is_null($datas['sort'])) {
                    $datas['sort'] = $this->model->count() + 1;
                }
            }

            // 記錄更新人員
            if (in_array('update_user_id', $this->model->getFillable())) {
                $datas['update_user_id'] = auth()->id();
            }

            // 更新或新增
            if (count($datas)) {
                if ($id) {
                    $this->model->find($id)->update($datas);
                    $debug_str = '更新';
                } else {
                    $model_res = $this->model->create($datas);
                    $id = $model_res->id;
                    $debug_str = '新增';
                }
                $this->execute_update_datas = $datas;
            } else {
                $debug_str = '未輸入資料';
            }

            // 除錯訊息
            if ($this->debug) {
                dd($debug_str . $id, $datas);
            }
            return $id;
        } else {
            Log::info('BaseRepository::executeUpdate', ['id' => $id, 'datas' => $datas, 'request' => request()->all()]);
        }
        return false;
    }

    /**
     * 檔案上傳
     *
     * $file_name   string  檔名
     */
    public function uploadFile($id = 0)
    {
        $datas = false;
        if (is_array($this->upload_file_form_name)) {
            if (count($this->upload_file_form_name)) {
                foreach ($this->upload_file_form_name as $key => $value) {

                    // 判斷是否為圖檔
                    if (FileService::isImage($value)) {
                        $res = ImageService::upload($value, $this->upload_file_name_prefix, $this->upload_file_size_limit, $this->upload_file_resize, $this->upload_file_folder);
                    } else {
                        $res = FileService::upload($value, $this->upload_file_name_prefix);
                    }
                    if ($res) {
                        $datas[$value] = $res['file_name'];
                        
                        // 檔案大小
                        if (isset($this->upload_file_size_column_name[$key])) {
                            $datas[$this->upload_file_size_column_name[$key]] = $res['file_size'];
                        }

                        // 不含副檔名原始檔名
                        if (isset($this->upload_file_origin_column_name[$key])) {
                            $datas[$this->upload_file_origin_column_name[$key]] = $res['origin_name'];
                        }

                        // 副檔名
                        if (isset($this->upload_file_extention_column_name[$key])) {
                            $datas[$this->upload_file_extention_column_name[$key]] = $res['file_extention'];
                        }

                        // 刪除舊檔
                        if ($id) {
                            $this->deleteFile($this->model->find($id)->{$value});
                        }
                    }
                }
            }
        } elseif (!empty($this->upload_file_form_name)) {
            $res = false;
            if ($this->upload_file_resize) {
                $res = ImageService::upload($this->upload_file_form_name, $this->upload_file_name_prefix, $this->upload_file_size_limit, $this->upload_file_resize, $this->upload_file_folder);
            } else {
                $res = FileService::upload($this->upload_file_form_name, $this->upload_file_name_prefix, $this->upload_file_size_limit, $this->upload_file_resize, $this->upload_file_folder);
            }
            if ($res) {
                if (empty($this->upload_file_column_name)) {
                    $datas[$this->upload_file_form_name] = $res['file_name'];
                } else {
                    $datas[$this->upload_file_column_name] = $res['file_name'];
                }

                // 檔案大小
                if (!empty($this->upload_file_size_column_name)) {
                    $datas[$this->upload_file_size_column_name] = $res['file_size'];
                }
                
                // 不含副檔名原始檔名
                if (!empty($this->upload_file_origin_column_name)) {
                    $datas[$this->upload_file_origin_column_name] = $res['origin_name'];
                }

                // 副檔名
                if (!empty($this->upload_file_extention_column_name)) {
                    $datas[$this->upload_file_extention_column_name] = $res['file_extention'];
                }

                // 標題欄位未填，自動以檔名做為標題
                if (!empty($this->upload_origin_file_column_name) && (!isset($datas[$this->upload_origin_file_column_name]) || empty($datas[$this->upload_origin_file_column_name]))) {
                    $datas[$this->upload_origin_file_column_name] = $res['origin_file_name'];
                }

                // 刪除舊檔
                if ($id) {
                    $this->deleteFile($this->model->find($id)->{$this->upload_file_form_name});
                }
            }
        }
        return $datas;
    }

    /**
     * 刪除檔案
     *
     * $file_name   string  檔名
     */
    public function deleteFile($file_name)
    {
        if (!empty($file_name)) {

            // 製作刪除檔案陣列
            $arr = [];
            if (Storage::disk('public')->has(config('frontend.upload_path') . '/' . $file_name)) {
                $arr[] = config('frontend.upload_path') . '/' . $file_name;
            }
            foreach (config('backend.image_scale_setting') as $value) {
                if (Storage::disk('public')->has(config('frontend.upload_path') . '/' . $value['path'] . '/' . $file_name)) {
                    $arr[] = config('frontend.upload_path') . '/' . $value['path'] . '/' . $file_name;
                }
            }
            if (count($arr)) {
                return Storage::disk('public')->delete($arr);
            }
        }
        return false;
    }

    /**
     * 上傳檔案準備
     *
     * $prefix              string              檔名前綴
     * $form_name           string || array     表單名稱
     * $upload_origin_file_column_name   string              圖片標題欄位名稱
     */
    public function attach($prefix, $form_name = '', $upload_origin_file_column_name = '', $upload_img = true)
    {
        if (is_array($prefix)) {
            $this->upload_file_form_name = $prefix;
        } else {
            $this->upload_file_name_prefix = $prefix;
            $this->upload_file_form_name = $form_name;
            $this->upload_origin_file_column_name = $upload_origin_file_column_name;
        }
        $this->upload_img = $upload_img;
        return $this;
    }

    /**
     * 自訂查詢條件刪除
     */
    public function clear($key, $value, $trashed = false)
    {
        if ($trashed) {

            // 永久刪除
            return $this->model->withTrashed()->where($key, $value)->forceDelete();
        } else {

            // 一般刪除
            return $this->model->where($key, $value)->delete();
        }
    }

    /**
     * 刪除
     */
    public function delete($id, $trashed = false)
    {
        if ($trashed) {

            // 永久刪除
            return $this->model->withTrashed()->where('id', $id)->forceDelete();
        } else {

            // 一般刪除
            return $this->model->find($id)->delete();
        }
    }

    /**
     * 還原刪除
     */
    public function restore($id)
    {
        return $this->model->withTrashed()->where('id', $id)->restore();
    }

    /**
     * 查詢啟用資料
     */
    public function active()
    {
        return $this->model->where('active', 1);
    }

    /**
     * 增加點擊
     */
    public function click($id)
    {
        $query = $this->model->find($id);
        $query->click = $query->click + 1;
        $query->save();
        return true;
    }

    /**
     * 列表查詢
     * 
     * $query       Object  查詢物件
     * $id          Int     目前版本 id
     * $paginate    Int     分頁筆數設定, 值為 0 時不使用分頁功能
     */
    function fetchList($query, $id, $paginate, $order_by = 'sort', $power = 'asc')
    {
        if ($this->trashed) {
            $query = $query->onlyTrashed();
        }
        if ($this->old_version) {
            if ($id) {
                $query = $query->whereOldVersion(1)->onlyTrashed()->where('origin_id', $id);
            } else {
                $query = $query->whereOldVersion(1)->onlyTrashed();
            }
        } else {
            if ($this->use_version) {
                $query = $query->whereOldVersion(0);
            }
        }
        if (!empty($order_by)) {
            $query = $query->orderBy($order_by, $power);
        }
        if ($paginate) {
            
            // 分頁設定
            $records_per_page = request('records_per_page', false);
            if ($records_per_page > 0) {
                session(['records_per_page' => $records_per_page]);
            }
            $query = $query->paginate(session('records_per_page', $paginate));
        } else {
            $query = $query->get();
        }
        if ($query->count()) {
            return $query;
        }
        return false;
    }

    /**
     * 單筆資料查詢
     */
    function fetchOne($query, $id)
    {
        if ($this->trashed) {
            $query = $query->onlyTrashed();
        }
        if ($this->old_version) {
            $query = $query->whereOldVersion(1)->onlyTrashed();
        } else {
            $query = $query->whereOldVersion(0);
        }
        $query = $query->where('id', $id)->first();
        if (!is_null($query)) {
            return $query;
        }
        return false;
    }

    /**
     * 設定日期區間查詢條件
     *
     * 同一欄位在兩個日期之前，年、月、日可單一條件無值
     * @param  object $q          [description]
     * @param  string $input_name [description]
     * @return [type]             [description]
     */
    public static function queryDateSelect($q, $input_name = '')
    {
        // 開始日
        $yyyy = request($input_name . '_yyyy', false);
        $mm = request($input_name . '_mm', false);
        $dd = request($input_name . '_dd', false);
        if ($yyyy || $mm || $dd) {
            if (!empty($yyyy)) {
                $q->whereYear($input_name, '>=', $yyyy);
            }
            if (!empty($mm)) {
                $q->whereMonth($input_name, '>=', $mm);
            }
            if (!empty($dd)) {
                $q->whereDay($input_name, '>=', $dd);
            }
        }

        // 結束日
        $yyyy_end = request($input_name . '_yyyy_end', false);
        $mm_end = request($input_name . '_mm_end', false);
        $dd_end = request($input_name . '_dd_end', false);
        if ($yyyy_end || $mm_end || $dd_end) {
            if (!empty($yyyy_end)) {
                $q->whereYear($input_name, '<=', $yyyy_end);
            }
            if (!empty($mm_end)) {
                $q->whereMonth($input_name, '<=', $mm_end);
            }
            if (!empty($dd_end)) {
                $q->whereDay($input_name, '<=', $dd_end);
            }
        }
        return $q;
    }

    /**
     * 設定時間區間查詢條件，比對一個欄位
     * @param  [type]         $q                  [description]
     * @param  string         $input_name         [description]
     * @param  string|boolean $time_start         [description]
     * @param  string|boolean $time_end           [description]
     * @return object                             [description]
     */
    public function queryTimeBetweenColumn($q, $input_name = '', $time_start = false, $time_end = false)
    {
        if ($time_start) {
            $q->where($input_name, '>=', date('H:i', strtotime($time_start . ':00')));
        }
        if ($time_end) {
            $q->where($input_name, '<', date('H:i', strtotime($time_end . ':00')));
        }
        return $q;
    }

    /**
     * 設定日期區間查詢條件，比對兩個欄位
     *
     * 月、日無輸入時，開始日將設定為 01 ，，結束日將設定為當月的最後一天
     * 年無輸入時，開始日將設定為 1911，結束日將設定為今年
     * @param  object         $q                        查詢物件
     * @param  string         $input_name               input 表單的欄位名稱，程式會自動加上 _yyyy，_mm，_dd，_yyyy_end，_mm_end，_dd_end, 六個欄位名稱的後綴
     * @param  string         $first_column_name        開始日期欄位的名稱
     * @param  string         $last_column_name         結束日期欄位的名稱
     * @return object                                   [description]
     */
    public function queryDateBetweenTwoColumn($q, $input_name = '', $first_column_name = '', $last_column_name = '')
    {
        // 以上方法有問題，重新製作
        $base_services = new BaseService;
        $yyyy = request($input_name . '_yyyy', false);
        $mm = request($input_name . '_mm', false);
        $dd = request($input_name . '_dd', false);
        if ($yyyy && $mm && $dd) {
            $date_start = $base_services->isDate([
                $yyyy,
                $mm,
                $dd
            ]);
            if ($date_start) {
                $q->where($first_column_name, '>=', $date_start);
            }
        }
        $yyyy_end = request($input_name . '_yyyy_end', false);
        $mm_end = request($input_name . '_mm_end', false);
        $dd_end = request($input_name . '_dd_end', false);
        if ($yyyy_end && $mm_end && $dd_end) {
            $date_end = $base_services->isDate([
                $yyyy_end,
                $mm_end,
                $dd_end
            ]);
            if ($date_end) {
                $q->where($last_column_name, '<=', $date_end);
            }
        }
        return $q;
    }

    /**
     * 批次處理
     * @return array  ['datas_count' => '處理成功的資料筆數', 'batch_method' => '批次的方法，供導回原頁面用']
     */
    public function batch($settings = [])
    {
        $result['datas_count'] = 0;
        $result['batch_method'] = '';
        $checked_id = request('checked_id', false);
        if ($checked_id && is_array($checked_id)) {
            $delete = request('delete', false);
            $force_delete = request('force_delete', false);
            $restore = request('restore', false);
            $hide = request('hide', false);
            $show = request('show', false);
            $status_enable = request('status_enable', false);
            $status_disable = request('status_disable', false);

            // 批次還原
            if ($restore) {
                $result['datas_count'] = $this->model->onlyTrashed()->whereIn('id', $checked_id)->restore();
                $result['batch_method'] = 'restore';
            }

            // 批次真實刪除
            if ($force_delete) {

                // 查詢所有選擇的資料，準備刪除附檔及版本
                $query = $this->model->onlyTrashed()->find($checked_id);
                
                // 刪除附檔
                if (isset($settings['file_field']) && isset($settings['folder'])) {
                    $upload_path = config('frontend.upload_path');
                    if (!empty($settings['folder'])) {
                        $upload_path .= '/' . $settings['folder'];
                    }
                    // $query = $this->model->onlyTrashed()->whereIn('id', $checked_id)->get();

                    // 製作刪除檔案陣列
                    $arr = [];
                    foreach ($query as $key => $value) {
                        $file_name = $value->{$settings['file_field']};
                        $arr[] = $upload_path . '/' . $file_name;
                        
                        // 刪除縮圖
                        if (isset($settings['image_scale'])) {
                            foreach (config('backend.image_scale_setting') as $value) {
                                $arr[] = $upload_path . '/' . $value['path'] . '/' . $file_name;
                            }
                        }
                    }
                    if (count($arr)) {
                        Storage::disk('public')->delete($arr);
                    }
                }

                // 刪除版本
                if (isset($settings['use_version']) && $settings['use_version']) {
                    foreach ($query as $key => $value) {
                        $this->model->onlyTrashed()->where('origin_id', $value->id)->forceDelete();
                    }
                }
                $result['datas_count'] = $this->model->onlyTrashed()->whereIn('id', $checked_id)->forceDelete();
                $result['batch_method'] = 'force_delete';
            }

            // 批次刪除
            if ($delete) {
                // $result['datas_count'] = $this->model->whereIn('id', $checked_id)->delete();
                $result['datas_count'] = $this->model->destroy($checked_id);
                $result['batch_method'] = 'delete';
            }

            // 批次設定顯示
            if ($show) {
                $result['datas_count'] = $this->model->whereIn('id', $checked_id)->update([config('site.frontend_active_column') => 1]);
                $result['batch_method'] = 'show';
            }

            // 批次設定隱藏
            if ($hide) {
                $result['datas_count'] = $this->model->whereIn('id', $checked_id)->update([config('site.frontend_active_column') => 0]);
                $result['batch_method'] = 'hide';
            }

            // 批次設定啟用
            if ($status_enable) {
                $result['datas_count'] = $this->model->whereIn('id', $checked_id)->update(['status' => '啟用']);
                $result['batch_method'] = 'show';
            }

            // 批次設定停用
            if ($status_disable) {
                $result['datas_count'] = $this->model->whereIn('id', $checked_id)->update(['status' => '停用']);
                $result['batch_method'] = 'hide';
            }
        }

        // 修改排序
        $set_sort = request('set_sort', false);
        if ($set_sort) {
            $sort = request('sort', false);
            if (is_array($sort)) {
                foreach ($sort as $key => $value) {

                    // 處理全形數字
                    $value = mb_convert_kana($value, 'n');
                    if (preg_match('/[0-9]+/', $value)) {
                        $this->model->where('id', $key)->update(['sort' => $value]);
                    }
                }
            }
            session()->flash('notify.message', __('backend.修改排序完成'));
            session()->flash('notify.type', 'success');
            // $result['datas_count'] = count($set_sort);
            $result['batch_method'] = 'set_sort';
        }
        return $result;
    }

    /**
     * 版本還原
     */
    public function applyVersion($id, $version_id)
    {
        // 備份現有資料後刪除
        $q = $this->model->find($id);
        $newVersion = $q->replicate();
        $newVersion->old_version = 1;
        $newVersion->origin_id = $id;
        $newVersion->save();
        $newVersion->delete();
        $q->forceDelete();

        // 將要還原的資料更新成原資料的 id
        $q = $this->model->withTrashed()->find($version_id);
        $q->old_version = 0;
        $q->id = $id;
        $q->origin_id = 0;
        // $q->note = 'Reduction';
        $q->deleted_at = NULL;
        $q->save();
        // $q->restore();
        session()->flash('notify.message', __('backend.版本還原完成'));
        session()->flash('notify.type', 'success');
    }

    /**
     * 下上排序修改
     */
    public function rearrange()
    {
        $method = request('method', false);
        $position = request('position', false);
        $sort_array = session('sort_array', false);
        if ($method && $position !== false && $sort_array) {
            if ($method == 'down') {
                $this->model->find($sort_array[$position][0])->update(['sort' => $sort_array[$position][1] + 1]);
                if (isset($sort_array[$position + 1])) {
                    $this->model->find($sort_array[$position + 1][0])->update(['sort' => $sort_array[$position + 1][1] - 1]);
                }
            } else {
                if ($sort_array[$position][1] > 1) {
                    $this->model->find($sort_array[$position][0])->update(['sort' => $sort_array[$position][1] - 1]);
                }
                if (isset($sort_array[$position - 1])) {
                    $this->model->find($sort_array[$position - 1][0])->update(['sort' => $sort_array[$position - 1][1] + 1]);
                }
            }
        }
    }
}
