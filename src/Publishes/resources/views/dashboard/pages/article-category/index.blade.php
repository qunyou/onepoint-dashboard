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
                <a href="{{ url(config('dashboard.uri') . '/article-category/index') }}">
                    @lang('article.文章分類')
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
                'with' => [
                    [
                        'with_count_string' => 'article_count',
                        'with_name' => __('article.文章'),
                        'url' => config('dashboard.uri') . '/article/index?article_category_id=',
                        'icon' => 'fas fa-list'
                    ],
                ],
                'uri' => $uri,
                'id_string' => 'article_category_id',
                'footer_dropdown_hide' => $footer_dropdown_hide,
                'footer_sort_hide' => $footer_sort_hide,
                'footer_delete_hide' => $footer_delete_hide,
                'qs' => $qs,
                'use_version' => $use_version ?? false,
                'use_duplicate' => $use_duplicate ?? false,
                'column' => [
                    'category_name',
                    'status'
                ],
            ])
                @slot('th')
                    <th scope="col">@lang('backend.分類名稱')</th>
                    <th scope="col" class="d-none d-md-table-cell">@lang('backend.狀態')</th>
                @endslot
            @endcomponent
        @endif
    @endcomponent
@endsection
