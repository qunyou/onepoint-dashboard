<?php

namespace App\Http\Controllers;

use Hash;
use Auth;
use Cache;
use Onepoint\Dashboard\Services\BaseService;
use App\Repositories\UserRepository;
use App\Repositories\RoleRepository;

/**
 * 人員
 */
class UserController extends Controller
{
    /**
     * 建構子
     */
    function __construct(BaseService $base_services, UserRepository $user_repository)
    {
        $this->base_services = $base_services;
        $this->tpl_data = $base_services->tpl_data;
        $this->tpl_data['base_services'] = $this->base_services;
        $this->permission_controller_string = get_class($this);
        $this->tpl_data['component_datas']['permission_controller_string'] = $this->permission_controller_string;
        $this->tpl_data['navigation_item'] = config('backend.navigation_item');

        $this->user_repository = $user_repository;

        // 預設網址
        $this->uri = config('dashboard.uri') . '/user/';
        $this->tpl_data['uri'] = $this->uri;
        $this->tpl_data['component_datas']['uri'] = $this->uri;

        // view 路徑
        $this->view_path = config('dashboard.view_path') . '.pages.user.';
        $this->user_id = request('user_id', false);
        $this->tpl_data['user_id'] = $this->user_id;
    }

    /**
     * 列表
     */
    public function index()
    {
        // 列表標題
        if (!$this->tpl_data['trashed']) {
            $this->tpl_data['component_datas']['page_title'] = __('backend.列表');
        } else {
            $this->tpl_data['component_datas']['page_title'] = __('backend.資源回收');
        }

        // 主資料 id query string 字串
        $this->tpl_data['component_datas']['id_string'] = 'user_id';

        // 回列表網址
        $this->tpl_data['component_datas']['back_url'] = url($this->uri . 'index');

        // 表格欄位設定
        $this->tpl_data['component_datas']['th'] = [
            ['title' => __('auth.姓名'), 'class' => ''],
            ['title' => __('auth.Email'), 'class' => 'd-none d-xl-table-cell'],
        ];
        $this->tpl_data['component_datas']['column'] = [
            ['type' => 'column', 'class' => '', 'column_name' => 'username'],
            ['type' => 'column', 'class' => 'd-none d-md-table-cell', 'column_name' => 'email'],
        ];

        // 是否使用複製功能
        $this->tpl_data['component_datas']['use_duplicate'] = true;

        // 是否使用版本功能
        $this->tpl_data['component_datas']['use_version'] = true;

        // 是否使用排序功能
        $this->tpl_data['component_datas']['use_sort'] = true;

        // 權限設定
        $this->tpl_data['component_datas']['footer_delete_hide'] = false;
        if (auth()->user()->hasAccess(['create-' . $this->permission_controller_string])) {
            $this->tpl_data['component_datas']['add_url'] = url($this->uri . 'update');
        }
        if (auth()->user()->hasAccess(['update-' . $this->permission_controller_string])) {
            $this->tpl_data['component_datas']['footer_dropdown_hide'] = false;
            $this->tpl_data['component_datas']['footer_sort_hide'] = false;
            if (!auth()->user()->hasAccess(['delete-' . $this->permission_controller_string])) {
                $this->tpl_data['component_datas']['footer_delete_hide'] = true;
            }
        } else {
            $this->tpl_data['component_datas']['footer_dropdown_hide'] = true;
            $this->tpl_data['component_datas']['footer_sort_hide'] = true;
        }
        if (auth()->user()->hasAccess(['create-' . $this->permission_controller_string])) {
            $this->tpl_data['component_datas']['dropdown_items']['items']['匯入'] = ['url' => url($this->uri . 'import')];
        }
        if (auth()->user()->hasAccess(['update-' . $this->permission_controller_string])) {
            $this->tpl_data['component_datas']['dropdown_items']['items']['舊版本'] = ['url' => url($this->uri . 'index?version=true')];
        }
        if (auth()->user()->hasAccess(['delete-' . $this->permission_controller_string])) {
            $this->tpl_data['component_datas']['dropdown_items']['items']['資源回收'] = ['url' => url($this->uri . 'index?trashed=true')];
        }

        // 列表資料查詢
        $this->tpl_data['component_datas']['list'] = $this->user_repository->getList($this->user_id, config('backend.paginate'));
        $this->tpl_data['component_datas']['qs'] = $this->base_services->getQueryString();

        // 預覽按鈕網址
        // $this->tpl_data['component_datas']['preview_url'] = ['url' => url(config('backend.book.preview_url')) . '/', 'column' => 'book_name_slug'];
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
        $result = $this->user_repository->batch($settings);
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
        $this->tpl_data['user'] = false;
        $role_repository = new RoleRepository;
        $category_id_array = [];

        // 分類值陣列
        if ($this->user_id) {
            $page_title = __('backend.編輯');
            $user = $this->user_repository->getOne($this->user_id);
            $this->tpl_data['user'] = $user;
            $role_id = 0;
            if ($user) {
                $category_id_array = $user->roles->pluck('id')->toArray();
            }

            // 複製
            if (isset($this->tpl_data['duplicate']) && $this->tpl_data['duplicate']) {
                $password_help = '';
                $page_title = __('backend.複製');
                $this->tpl_data['duplicate'] = true;
            } else {
                $password_help = __('auth.若不修改密碼請保持密碼欄位空白');
            }
        } else {
            $page_title = __('backend.新增');
            $password_help = '';
        }

        // 分類選單資料
        $category_select_item = $role_repository->getOptionItem();

        // 一般表單資料
        $this->tpl_data['form_array'] = [
            'realname' => [
                'input_type' => 'text',
                'display_name' => __('auth.姓名'),
            ],
            'role_id' => [
                'input_type' => 'checkbox',
                'display_name' => __('auth.群組'),
                'input_value' => $category_id_array,
                'option' => $category_select_item,
                // 'attribute' => ['multiple' => 'multiple', 'size' => 5],
                // 'help' => '按著Ctrl點選，可複選多個項目',
            ],
            'username' => [
                'input_type' => 'text',
                'display_name' => __('auth.帳號'),
            ],
            'email' => [
                'input_type' => 'text',
                'display_name' => __('auth.Email'),
            ],
            'password' => [
                'input_type' => 'password',
                'input_value' => '',
                'display_name' => __('auth.密碼'),
                'help' => $password_help,
                'attribute' => ['autocomplete' => 'off'],
            ],
            'password_confirmation' => [
                'input_type' => 'password',
                'input_value' => '',
                'display_name' => __('auth.密碼確認'),
                'help' => __('auth.請再輸入一次密碼'),
                'attribute' => ['autocomplete' => 'off'],
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
        $res = $this->user_repository->setUpdate($this->user_id, false);
        if ($res) {
            session()->flash('notify.message', __('backend.資料編輯完成'));
            session()->flash('notify.type', 'success');
            return redirect($this->uri . 'detail?user_id=' . $res);
        } else {
            session()->flash('notify.message', __('backend.資料編輯失敗'));
            session()->flash('notify.type', 'danger');
            return redirect($this->uri . 'update?user_id=' . $this->user_id);
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
        $this->user_id = 0;
        return $this->putUpdate();
    }

    /**
     * 細節
     */
    public function detail(RoleRepository $role_repository)
    {
        if ($this->user_id) {
            $user = $this->user_repository->getOne($this->user_id);
            $this->tpl_data['user'] = $user;
            $category_value_str = $user->roles->implode('role_name', ', ');

            // 表單資料
            $this->tpl_data['form_array'] = [
                'realname' => [
                    'input_type' => 'value',
                    'display_name' => __('auth.姓名'),
                ],
                'role_id' => [
                    'input_type' => 'value',
                    'display_name' => __('auth.群組'),
                    'input_value' => $category_value_str
                ],
                'username' => [
                    'input_type' => 'value',
                    'display_name' => __('auth.帳號'),
                ],
                'email' => [
                    'input_type' => 'value',
                    'display_name' => __('auth.Email'),
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
                $component_datas['dropdown_items']['items']['編輯'] = ['url' => url($this->uri . 'update?user_id=' . $user->id)];
            }
            if (auth()->user()->hasAccess(['create-' . $this->permission_controller_string])) {
                $component_datas['dropdown_items']['items']['複製'] = ['url' => url($this->uri . 'duplicate?user_id=' . $user->id)];
            }
            if (auth()->user()->hasAccess(['delete-' . $this->permission_controller_string])) {
                $component_datas['dropdown_items']['items']['刪除'] = ['url' => url($this->uri . 'delete?user_id=' . $user->id)];
            }
            // $component_datas['dropdown_items']['items']['預覽'] = ['url' => url(config('backend.user.preview_url', 'detail/') . $user->id)];
            $this->tpl_data['component_datas'] = $component_datas;
            return view($this->view_path . 'detail', $this->tpl_data);
        } else {
            return redirect($this->uri . 'index');
        }
    }

    /**
     * 刪除
     */
    public function delete()
    {
        if ($this->user_id) {
            $this->user_repository->delete($this->user_id);
        }
        return redirect($this->uri . 'index');
    }

    /**
     * 修改排序
     */
    public function rearrange()
    {
        if ($this->user_id) {
            $this->user_repository->rearrange();
        }
        return redirect($this->uri . 'index');
    }

    /**
     * 登入者資料維護
     */
    public function profile()
    {
        $this->tpl_data['user'] = false;
        $role_repository = new RoleRepository;
        $category_id_array = [];

        // 分類值陣列
        if (Auth::id()) {
            $page_title = __('backend.編輯');
            $user = $this->user_repository->getOne(Auth::id());
            $this->tpl_data['user'] = $user;
            $role_id = 0;
            if ($user) {
                $category_id_array = $user->roles->pluck('id')->toArray();
            }
            if ($this->duplicate) {
                $password_help = '';
            } else {
                $password_help = __('auth.若不修改密碼請保持密碼欄位空白');
            }
        } else {
            exit;
        }

        // 分類選單資料
        $category_select_item = $role_repository->getOptionItem();

        // 一般表單資料
        $this->tpl_data['form_array'] = [
            'realname' => [
                'input_type' => 'text',
                'display_name' => __('auth.姓名'),
            ],
            'role_id' => [
                'input_type' => 'checkbox',
                'display_name' => __('auth.群組'),
                'input_value' => $category_id_array,
                'option' => $category_select_item,
                // 'attribute' => ['multiple' => 'multiple', 'size' => 5],
                // 'help' => '按著Ctrl點選，可複選多個項目',
            ],
            'username' => [
                'input_type' => 'text',
                'display_name' => __('auth.帳號'),
            ],
            'email' => [
                'input_type' => 'text',
                'display_name' => __('auth.Email'),
            ],
            'password' => [
                'input_type' => 'password',
                'input_value' => '',
                'display_name' => __('auth.密碼'),
                'help' => $password_help,
                'attribute' => ['autocomplete' => 'off'],
            ],
            'password_confirmation' => [
                'input_type' => 'password',
                'input_value' => '',
                'display_name' => __('auth.密碼確認'),
                'help' => __('auth.請再輸入一次密碼'),
                'attribute' => ['autocomplete' => 'off'],
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

        // 樣版資料
        $component_datas['page_title'] = $page_title;
        $component_datas['back_url'] = url($this->uri . 'index');
        $this->tpl_data['component_datas'] = $component_datas;
        return view($this->view_path . 'profile', $this->tpl_data);
    }

    /**
     * 個人資料維護-接收資料
     */
    public function putProfile()
    {
        // 檢查帳號是否重複
        $email = $this->user_repository->model->where('email', request('email'))->where('id', '<>', Auth::id())->first();
        if (!is_null($email)) {
            $this->user_repository->rememberErrors('email', '人員帳號重複');
        } else {
            $datas = request()->all();
            if (!blank($datas['password'])) {
                $datas['password'] = Hash::make($datas['password']);
            } else {
                unset($datas['password']);
            }
            if (!blank($datas['email'])) {
                $datas['email'] = $datas['email'];
            } else {
                unset($datas['email']);
            }
            Auth::user()->update($datas);
            session()->flash('notify.message', '資料更新完成');
            session()->flash('notify.type', 'success');
        }
        return redirect($this->uri . 'profile');
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
     *
     * @return \Illuminate\Http\Response
     */
    public function putImport()
    {
        // 匯入時間
        $created_at = date('Y-m-d H:i:s', time());

        // 匯入訊息
        $import_message = [];

        // 上傳檔案
        $prefix = 'user-import-' . date('Y-m-d');
        $res = Tool::upload('file_name', $prefix);

        // 檢查是否多檔上傳
        if (isset($res[0]['file_name'])) {
            set_time_limit(120);
            foreach ($res as $value) {

                // 開啟檔案
                $file = fopen(public_path() . '/' . config('frontend.upload_path') . '/' . $value['file_name'], 'r');
                $key = 0;
                while (!feof($file)) {
                    $new_user_datas = [];
                    $csv_value = fgetcsv($file);

                    // 家族 B
                    $family = isset($csv_value[1]) ? $csv_value[1] : '';

                    // 暱稱 C
                    $nickname = isset($csv_value[2]) ? $csv_value[2] : '';

                    // 姓名 D
                    $realname = isset($csv_value[3]) ? $csv_value[3] : '';

                    // 推薦人 F
                    $user_name = isset($csv_value[5]) ? $csv_value[5] : '';

                    // 電話 G
                    $tel = isset($csv_value[6]) ? $csv_value[6] : '';

                    // email H
                    $email = isset($csv_value[7]) ? $csv_value[7] : '';

                    // 平台 I
                    $platform_name = isset($csv_value[8]) ? $csv_value[8] : '';

                    // 自我簡介 J
                    $self_introduction = isset($csv_value[9]) ? $csv_value[9] : '';

                    // 群組 K
                    $role_string = isset($csv_value[10]) ? $csv_value[10] : '';

                    // 略過第一列資料
                    if ($key >= 1) {

                        // 檢查 mail 是否重覆
                        $email_repeat_check = true;
                        $user = User::where('email', $email)->first();
                        if (!is_null($user)) {
                            $email_repeat_check = false;
                        }

                        // 必填欄位檢查
                        if ($email_repeat_check && !empty($realname) && !empty($email)) {

                            // 家族
                            $new_user_datas['family'] = $family;

                            // 暱稱
                            $new_user_datas['nickname'] = $nickname;

                            // 姓名
                            $new_user_datas['realname'] = $realname;

                            // 推薦人
                            $user_id = 0;
                            if (!empty($user_name)) {
                                $user = User::where('realname', $user_name)->first();
                                if (!is_null($user)) {
                                    $user_id = $user->id;
                                }
                            }
                            $new_user_datas['user_id'] = $user_id;

                            // 電話
                            $new_user_datas['tel'] = $tel;

                            // email
                            $new_user_datas['email'] = $email;

                            // 自我簡介
                            $new_user_datas['self_introduction'] = $self_introduction;

                            // 自動產生帳號
                            $query = User::select('id')->orderBy('id', 'desc')->first();
                            $member_count = $query->id + 1;
                            $code = date('Ymd') . str_pad($member_count, 4, '0', STR_PAD_LEFT);
                            $new_user_datas['username'] = $code;

                            // 密碼為當天日期
                            $new_user_datas['password'] = date('Ymd');

                            // 群組
                            $role_id_array = [];
                            if (!empty($role_string)) {
                                $role_string_array = explode(',', $role_string);
                                foreach ($role_string_array as $role_name) {
                                    $role = Role::where('name', $role_name)->first();
                                    if (!is_null($role)) {
                                        $role_id_array[] = $role->id;
                                    }
                                }
                            }

                            // 執行匯入
                            $new_user_id = $this->user_repository->setUpdate(0, $new_user_datas, $role_id_array, false);

                            // 查詢平台 id
                            $platform = Platform::where('name', $platform_name)->first();
                            if (!is_null($platform)) {
                                $user_platform_datas['user_id'] = $new_user_id;
                                $user_platform_datas['platform_id'] = $platform->id;
                                $user_platform_datas['nickname'] = $nickname;
                                UserPlatform::create($user_platform_datas);
                            }
                            $import_message[] = '檔案 ' . $value['origin_name'] . '第' . $key . '列資料匯入完成';
                        } else {
                            $massage_str = '';
                            if ($email_repeat_check) {
                                $massage_str .= 'email 帳號重複，';
                            }
                            if (empty($realname)) {
                                $massage_str .= '姓名為必填資料，';
                            }
                            if (empty($email)) {
                                $massage_str .= 'email 為必填資料，';
                            }
                            $import_message[] = '檔案 ' . $value['origin_name'] . '第' . $key . '列資料匯入失敗' . $massage_str;
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
