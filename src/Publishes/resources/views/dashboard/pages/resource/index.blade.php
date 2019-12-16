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
                <a href="{{ url(config('dashboard.uri') . '/resource-category/index') }}">
                    @lang('resource.資源分類')
                </a>
            </li>
            <li class="breadcrumb-item" aria-current="page">
                <a href="{{ url(config('dashboard.uri') . '/resource/index') }}">
                    @lang('resource.資源列表')
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
                'id_string' => 'resource_id',
                'footer_dropdown_hide' => $footer_dropdown_hide,
                'footer_sort_hide' => $footer_sort_hide,
                'footer_delete_hide' => $footer_delete_hide,
                'qs' => $qs,
                'use_version' => $use_version ?? false,
                'use_duplicate' => $use_duplicate ?? false,
                'column' => [
                    // key0 : 類型
                    // key1 : 主欄位名稱
                    // key2 : badge 設定值
                    ['badges', 'resource_title', [
                        'click' => ['class' => 'badge badge-secondary', 'badge_title' => '點擊:']
                    ]],

                    // key0 : 類型
                    // key1 : 關聯資料表
                    // key2 : 關聯資料表欄位名稱
                    ['belongsToMany', 'resource_category', 'category_name'],
                    'resource_url'
                ],
            ])
                @slot('th')
                    <th scope="col">@lang('backend.標題')</th>
                    <th scope="col" class="d-none d-md-table-cell">@lang('backend.分類')</th>
                    <th scope="col" class="d-none d-md-table-cell">@lang('backend.連結網址')</th>
                @endslot
            @endcomponent
        @endif
    @endcomponent
@endsection