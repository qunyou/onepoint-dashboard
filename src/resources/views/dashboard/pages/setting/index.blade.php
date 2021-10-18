{{-- @inject('setting_presenter', 'Onepoint\Dashboard\Presenters\SettingPresenter') --}}

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
    @component('dashboard::' . config('backend.template') . '.components.backend-list', $component_datas)
        @slot('button_block')
            <div class="btn-group">
                <a class="btn btn-outline-deep-purple waves-effect" href="{{ url($uri . 'update?model=' . request('model', '')) }}">新增設定</a>
                @foreach (config('backend.setting.model') as $setting_model => $setting_title)
                    <a class="btn btn-primary{{ request('model', '') == $setting_model ? ' active' : '' }}" href="{{ url($uri . 'index?model=' . $setting_model) }}">{{ $setting_title }}</a>
                @endforeach
            </div>

            {{-- <div class="dropdown">
                <button class="btn btn-outline-deep-purple waves-effect dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    @lang('dashboard::setting.項目類別')
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    @foreach (config('backend.setting.model') as $setting_model => $setting_title)
                        <a class="dropdown-item{{ request('model', '') == $setting_model ? ' active' : '' }}" href="{{ url($uri . 'index?model=' . $setting_model) }}">{{ $setting_title }}</a>
                    @endforeach
                </div>
            </div> --}}
        @endslot
    @endcomponent
@endsection
