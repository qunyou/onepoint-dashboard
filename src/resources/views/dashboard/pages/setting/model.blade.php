@extends('dashboard::layouts.dashboard')

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
                <a href="{{ url(config('dashboard.uri') . '/setting/model') }}">
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
        @slot('page_title')
            @if (!$trashed)
                @lang('dashboard::setting.設定列表')
            @else
                @lang('dashboard::backend.資源回收')
            @endif
        @endslot
        @slot('button_block')
            @if (count(config('backend.setting.model')) > 1)
                <div class="btn-group d-block d-md-inline-block">
                    <div class="dropdown">
                        <button class="btn btn-outline-deep-purple waves-effect dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            @lang('dashboard::setting.項目類別')
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            @foreach (config('backend.setting.model') as $setting_model => $setting_title)
                                <a class="dropdown-item{{ request('model', '') == $setting_model ? ' active' : '' }}" href="{{ url($uri . 'model?model=' . $setting_model) }}">
                                    {{ $setting_title }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        @endslot
    @endcomponent
@endsection
