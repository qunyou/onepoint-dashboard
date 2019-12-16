@extends('dashboard::layouts.dashboard')

@section('page-header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item" aria-current="page">
                <a href="{{ url(config('dashboard.uri') . '/dashboard/index') }}">
                    Home
                </a>
            </li>
            <li class="breadcrumb-item" aria-current="page">
                <a href="{{ url(config('dashboard.uri') . '/album-category/index') }}">
                    @lang('album.相簿分類')
                </a>
            </li>
            <li class="breadcrumb-item" aria-current="page">
                <a href="{{ url(config('dashboard.uri') . '/album/index') }}">
                    @lang('album.相簿列表')
                </a>
            </li>
            <li class="breadcrumb-item" aria-current="page">
                <a href="#">
                    @lang('album.相片列表')
                </a>
            </li>

            @if ($version)
                <li class="breadcrumb-item" aria-current="page">
                    <a href="#">
                        @lang('backend.版本檢視')
                    </a>
                </li>
            @endif
        </ol>
    </nav>
@endsection

@section('main_block')
    @component('dashboard::components.backend-list-card', $component_datas)
        @if ($list)
            @component('dashboard::components.backend-list-table', [
                'permission_controller_string' => $permission_controller_string,
                'trashed' => $trashed,
                'version' => $version,
                'list' => $list,
                'uri' => $uri,
                'id_string' => 'album_image_id',
                'footer_dropdown_hide' => $footer_dropdown_hide,
                'footer_sort_hide' => $footer_sort_hide,
                'footer_delete_hide' => $footer_delete_hide,
                'qs' => $qs,
                'use_version' => $use_version ?? false,
                'use_duplicate' => $use_duplicate ?? false,
                'update_url_append_string' => $update_url_append_string,
                'column' => [
                    'album_images_title',

                    // key0 : 類型
                    // key1 : 縮圖方法
                    // key2 : 欄位名稱
                    // key3 : 資料夾名稱
                    ['image', 'thumb', 'file_name', 'album'],

                    // key0 : 類型
                    // key1 : 關聯資料表
                    // key2 : 關聯資料表欄位名稱
                    ['belongsToMany', 'album', 'album_title'],
                ],
            ])
                @slot('th')
                    <th scope="col">@lang('backend.標題')</th>
                    <th scope="col" class="d-none d-md-table-cell">@lang('backend.圖片預覽')</th>
                    <th scope="col" class="d-none d-md-table-cell">@lang('album.相簿')</th>
                @endslot
            @endcomponent
        @endif
    @endcomponent
@endsection
