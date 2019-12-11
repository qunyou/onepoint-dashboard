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
                <a href="{{ url(config('dashboard.uri') . '/user/index') }}">
                    @lang('auth.人員管理')
                </a>
            </li>

            @if ($version)
                <li class="breadcrumb-item" aria-current="page">
                    <a href="#">
                        @lang('dashboard.版本檢視')
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
                'id_string' => 'user_id',
                'footer_dropdown_hide' => $footer_dropdown_hide,
                'footer_sort_hide' => $footer_sort_hide,
                'footer_delete_hide' => $footer_delete_hide,
                'qs' => $qs,
                'use_version' => $use_version ?? false,
                'use_duplicate' => $use_duplicate ?? false,
                'column' => [
                    'username',
                    ['belongsToMany', 'roles', 'role_name'],
                    'status'
                ],
            ])
                @slot('th')
                    <th scope="col">@lang('auth.帳號')</th>
                    <th scope="col" class="d-none d-md-table-cell">@lang('auth.群組')</th>
                    <th scope="col" class="d-none d-md-table-cell">@lang('backend.狀態')</th>
                @endslot
            @endcomponent
        @endif
    @endcomponent
@endsection
