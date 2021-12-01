@extends('dashboard::' . config('backend.template') . '.layouts.dashboard')

@section('page-header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item" aria-current="page">
                <a href="{{ url(config('dashboard.uri') . '/dashboard/index') }}">
                    <i class="fas fa-home"></i>
                </a>
            </li>
            <li class="breadcrumb-item" aria-current="page">
                <a href="{{ url(config('dashboard.uri') . '/role/index') }}">
                    @lang('dashboard::auth.人員群組')
                </a>
            </li>
            @if ($version)
                <li class="breadcrumb-item active" aria-current="page">
                    @lang('dashboard::backend.版本檢視')
                </li>
            @endif
        </ol>
    </nav>
@endsection

@section('main_block')
    @component('dashboard::' . config('backend.template') . '.components.backend-list', $component_datas)
        @slot('button_block')
            @if (!config('user.use_role') || auth()->user()->hasAccess(['create-Onepoint\Base\Controllers\RoleController']))
                <a href="{{ url($uri . 'update?' . $base_service->getQueryString(true, true, ['role_id'])) }}" class="btn btn-primary">
                    <i class="fa fa-plus"></i>@lang('dashboard::auth.新增人員群組')
                </a>
            @endif
            {{-- <a class="btn btn-secondary" data-bs-toggle="collapse" href="#collapseSearch" role="button" aria-expanded="false" aria-controls="collapseSearch">
                @lang('dashboard::backend.查詢區塊')
            </a> --}}
        @endslot
    @endcomponent
@endsection
