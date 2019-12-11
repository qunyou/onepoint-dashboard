@extends(config('backend.view_path') . '.layouts.' . config('backend.layout_file'))

@section('title', config('site.name'))

@section('page-header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item" aria-current="page">
                <a href="{{ url(config('dashboard.uri') . '/dashboard/index') }}">
                    Home
                </a>
            </li>
            <li class="breadcrumb-item" aria-current="page">
                <a href="{{ url(config('dashboard.uri') . '/layout/index') }}">
                    @lang('layout.排版')
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
    @component('shared.components.backend-list-card', $component_datas)
        @if ($list)
            @component('shared.components.backend-list-table', [
                'permission_controller_string' => $permission_controller_string,
                'trashed' => $trashed,
                'version' => $version,
                'list' => $list,
                'uri' => $uri,
                'id_string' => 'layout_id',
                'footer_dropdown_hide' => $footer_dropdown_hide,
                'footer_sort_hide' => $footer_sort_hide,
                'footer_delete_hide' => $footer_delete_hide,
                'qs' => $qs,
                'use_version' => $use_version ?? false,
                'use_duplicate' => $use_duplicate ?? false,
                'column' => [
                    'layout_title',
                    'status'
                ],
            ])
                @slot('th')
                    <th scope="col">@lang('backend.標題')</th>
                    <th scope="col" class="d-none d-md-table-cell">@lang('backend.狀態')</th>
                @endslot
            @endcomponent
        @endif
    @endcomponent
@endsection
