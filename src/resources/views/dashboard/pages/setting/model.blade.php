@extends('dashboard::' . config('backend.template') . '.layouts.dashboard')

@section('top-item')
    @include('base::dashboard.includes.top-item')
    @parent
@endsection

@section('sidebar-header')
    @include('base::dashboard.includes.sidebar-header')
@endsection

@section('page-header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item" aria-current="page">
                <a href="{{ url(config('dashboard.uri') . '/dashboard/index') }}">
                    Home
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                <a href="{{ url(config('dashboard.uri') . '/setting/model') }}">
                    @lang('dashboard::setting.網站設定')
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
            @if (count(config('backend.setting.model')) > 1)
                <div class="btn-group">
                    @foreach (config('backend.setting.model') as $setting_model => $setting_title)
                        <a class="btn btn-primary{{ request('model', '') == $setting_model ? ' active' : '' }}" href="{{ url($uri . 'model?model=' . $setting_model) }}">{{ $setting_title }}</a>
                    @endforeach
                </div>
            @endif
        @endslot
    @endcomponent
@endsection
