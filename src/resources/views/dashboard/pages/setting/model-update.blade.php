@extends('dashboard::' . config('backend.template') . '.layouts.dashboard')

@section('page-header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item" aria-current="page">
                <a href="{{ url(config('dashboard.uri') . '/dashboard/index') }}">
                    Home
                </a>
            </li>
            <li class="breadcrumb-item" aria-current="page">
                <a href="{{ url(config('dashboard.uri') . '/setting/model-update') }}">
                    @lang('dashboard::setting.網站設定')
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                @if ($setting_id)
                    @if (isset($duplicate) && $duplicate)
                        @lang('dashboard::backend.複製')
                    @else
                        @lang('dashboard::backend.編輯')
                    @endif
                @else
                    @lang('dashboard::backend.新增')
                @endif
            </li>
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
        <div class="px-2 py-3 overflow-x">
            <input type="hidden" name="model" value="{{ $model }}">
        </div>
        @include('dashboard::value', ['input_setting' => [
            'display_name' => __('dashboard::backend.標題'),
            'input_name' => 'title',
            'input_value' => optional($setting)->title,
        ]])

        @include('dashboard::value', ['input_setting' => [
            'display_name' => __('dashboard::backend.說明'),
            'input_name' => 'description',
            'input_value' => optional($setting)->description,
        ]])

        @switch(optional($setting)->type)
            @case('num')
                {{-- 數字 --}}
                @include('dashboard::number', ['input_setting' => [
                    'display_name' => __('dashboard::setting.設定值'),
                    'input_name' => 'setting_value',
                    'input_value' => optional($setting)->setting_value,
                ]])
                @break

            @case('str')
                {{-- 文字 --}}
                @include('dashboard::text', ['input_setting' => [
                    'display_name' => __('dashboard::setting.設定值'),
                    'input_name' => 'setting_value',
                    'input_value' => optional($setting)->setting_value,
                ]])
                @break

            @case('text')
                {{-- 文字區 --}}
                @include('dashboard::textarea', ['input_setting' => [
                    'display_name' => __('dashboard::setting.設定值'),
                    'input_name' => 'setting_value',
                    'input_value' => optional($setting)->setting_value,
                    'rows' => 10
                ]])
                @break

            @case('editor')
                {{-- 編輯器 --}}
                @include('dashboard::tinymce', ['input_setting' => [
                    'display_name' => __('dashboard::setting.設定值'),
                    'input_name' => 'setting_value',
                    'input_value' => optional($setting)->setting_value,
                ]])
                @break

            @case('file_name')
                {{-- 檔案上傳 --}}
                @include('dashboard::file', ['input_setting' => [
                    'display_name' => __('dashboard::setting.設定值'),
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
                    'display_name' => __('dashboard::setting.設定值'),
                    'input_name' => 'setting_value',
                    'input_value' => optional($setting)->setting_value,
                ]])
                @break
        @endswitch

        @include('dashboard::number', ['input_setting' => [
            'display_name' => __('dashboard::backend.排序'),
            'input_name' => 'sort',
            'input_value' => optional($setting)->sort,
        ]])
        <button type="submit" class="btn btn-primary">
            {{__('dashboard::backend.送出') }}
        </button>
    </form>
@endsection
