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
                <a href="{{ url(config('dashboard.uri') . '/setting/index') }}">
                    @lang('dashboard::setting.網站設定')
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                <a href="{{ url($uri . 'detail?setting_id=' . $setting_id) }}">
                    @lang('dashboard::backend.檢視')
                    @if ($version)
                        - @lang('dashboard::backend.版本檢視')
                    @endif
                </a>
            </li>

            @if ($version)
                <li class="breadcrumb-item" aria-current="page">
                    <a href="#">
                        @lang('dashboard::backend.版本檢視')
                    </a>
                </li>
            @endif
        </ol>
    </nav>
@endsection

@section('main_block')
    @component('dashboard::' . config('backend.template') . '.components.backend-detail-card', $component_datas)
        @include('dashboard::backend-update-input', ['form_array' => $form_array, 'form_value' => $setting ?? ''])
    @endcomponent
@endsection
