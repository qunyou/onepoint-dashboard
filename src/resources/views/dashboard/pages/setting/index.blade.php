@inject('setting_presenter', 'Onepoint\Dashboard\Presenters\SettingPresenter')

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
    @component('dashboard::components.backend-list', $component_datas)
        @slot('search_block')
            <div class="dropdown">
                <button class="btn btn-outline-deep-purple waves-effect dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    @lang('dashboard::setting.項目類別')
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    @foreach (config('backend.setting.model') as $setting_model => $setting_title)
                        <a class="dropdown-item{{ request('model', '') == $setting_model ? ' active' : '' }}" href="{{ url($uri . 'index?model=' . $setting_model) }}">{{ $setting_title }}</a>
                    @endforeach
                </div>
            </div>
        @endslot
    @endcomponent
@endsection
