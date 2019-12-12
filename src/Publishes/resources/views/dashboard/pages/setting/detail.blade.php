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
                    @lang('setting.網站設定')
                </a>
            </li>

            <li class="breadcrumb-item" aria-current="page">
                <a href="#">
                    @lang('backend.檢視')
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
    <div class="row">
        <div class="col-md-12">
            <div class="card card-update">
                <div class="card-header">
                    <div class="row">
                        <div class="col">
                            <div class="card-title">{{ $page_title }}</div>
                        </div>
                        <div class="col">
                            <div class="float-right">
                                <div class="btn-group">
                                    <a class="btn btn-outline-deep-purple waves-effect" href="{{ url($uri . 'index') }}"><i class="fa fa-fw fa-arrow-left"></i>{{ trans('backend.回列表') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @include('dashboard::value', ['input_setting' => [
                        'display_name' => 'model',
                        'input_name' => 'model',
                        'input_value' => optional($setting)->model,
                    ]])

                    @include('dashboard::value', ['input_setting' => [
                        'display_name' => 'type',
                        'input_name' => 'type',
                        'input_value' => optional($setting)->type,
                    ]])

                    @include('dashboard::value', ['input_setting' => [
                        'display_name' => 'title',
                        'input_name' => __('backend.標題'),
                        'input_value' => optional($setting)->title,
                    ]])

                    @include('dashboard::value', ['input_setting' => [
                        'display_name' => 'description',
                        'input_name' => __('backend.說明'),
                        'input_value' => optional($setting)->description,
                    ]])

                    @include('dashboard::value', ['input_setting' => [
                        'display_name' => 'setting_key',
                        'input_name' => 'setting_key',
                        'input_value' => optional($setting)->setting_key,
                    ]])

                    @include('dashboard::value', ['input_setting' => [
                        'display_name' => 'setting_value',
                        'input_name' => 'setting_value',
                        'input_value' => optional($setting)->setting_value,
                    ]])

                    @include('dashboard::value', ['input_setting' => [
                        'display_name' => __('backend.排序'),
                        'input_name' => 'sort',
                        'input_value' => optional($setting)->sort,
                    ]])
                </div>
                <div class="card-footer">
                    @component('dashboard::components.dropdown-toggle', [
                            'items' => [
                                '編輯' => ['url' => url($uri . 'update?setting_id=' . optional($setting)->id)],
                            ],
                            'btn_align' => 'float-left'
                        ])
                        <a class="btn btn-outline-deep-purple waves-effect d-none d-md-inline" href="{{ url($uri . 'index?model=' . optional($setting)->model . '&setting_id=' . optional($setting)->id) }}">
                            <i class="fa fa-fw fa-arrow-left"></i>@lang('backend.回列表')
                        </a>
                    @endcomponent
                </div>
            </div>
        </div>
    </div>
@endsection
