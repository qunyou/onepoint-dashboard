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
                    @if ($setting_id)
                        @if (isset($duplicate) && $duplicate)
                            @lang('backend.複製')
                        @else
                            @lang('backend.編輯')
                        @endif
                    @else
                        @lang('backend.新增')
                    @endif
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

@section('js')
    @include('dashboard::laravel-filemanager')
@endsection

@section('main_block')
    <form method="post" action="" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="model" value="{{ $model }}">
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
                                        <a class="btn btn-outline-deep-purple waves-effect" href="{{ url($uri . 'model?model=' . $model) }}"><i class="fa fa-fw fa-arrow-left"></i>{{ __('backend.回列表') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @include('dashboard::value', ['input_setting' => [
                            'display_name' => __('backend.標題'),
                            'input_name' => 'title',
                            'input_value' => optional($setting)->title,
                        ]])

                        @include('dashboard::value', ['input_setting' => [
                            'display_name' => __('backend.說明'),
                            'input_name' => 'description',
                            'input_value' => optional($setting)->description,
                        ]])

                        @switch(optional($setting)->type)
                            @case('num')
                                {{-- 數字 --}}
                                @include('dashboard::number', ['input_setting' => [
                                    'display_name' => __('setting.設定值'),
                                    'input_name' => 'setting_value',
                                    'input_value' => optional($setting)->setting_value,
                                ]])
                                @break

                            @case('str')
                                {{-- 文字 --}}
                                @include('dashboard::text', ['input_setting' => [
                                    'display_name' => __('setting.設定值'),
                                    'input_name' => 'setting_value',
                                    'input_value' => optional($setting)->setting_value,
                                ]])
                                @break

                            @case('text')
                                {{-- 文字區 --}}
                                @include('dashboard::textarea', ['input_setting' => [
                                    'display_name' => __('setting.設定值'),
                                    'input_name' => 'setting_value',
                                    'input_value' => optional($setting)->setting_value,
                                    'rows' => 10
                                ]])
                                @break

                            @case('editor')
                                {{-- 編輯器 --}}
                                @include('dashboard::tinymce', ['input_setting' => [
                                    'display_name' => __('setting.設定值'),
                                    'input_name' => 'setting_value',
                                    'input_value' => optional($setting)->setting_value,
                                ]])
                                @break

                            @case('file_name')
                                {{-- 檔案上傳 --}}
                                @include('dashboard::file', ['input_setting' => [
                                    'display_name' => __('setting.設定值'),
                                    'input_name' => 'setting_value',
                                    'input_value' => optional($setting)->setting_value,
                                    'image_thumb' => false,
                                    'image_attribute' => ['style' => 'width:200px;'],
                                    'help' => $file_input_help,
                                ]])
                                @break

                            @case('color')
                                {{-- 色彩 --}}
                                @include('dashboard::color', ['input_setting' => [
                                    'display_name' => __('setting.設定值'),
                                    'input_name' => 'setting_value',
                                    'input_value' => optional($setting)->setting_value,
                                ]])
                                @break
                        @endswitch

                        @include('dashboard::number', ['input_setting' => [
                            'display_name' => __('backend.排序'),
                            'input_name' => 'sort',
                            'input_value' => optional($setting)->sort,
                        ]])
                    </div>
                    <footer class="card-footer">
                        <button type="submit" class="btn btn-outline-deep-purple waves-effect">
                            {{__('backend.送出') }}
                        </button>
                    </footer>
                </div>
            </div>
        </div>
    </form>
@endsection
