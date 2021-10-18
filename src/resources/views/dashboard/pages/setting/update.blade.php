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
                <a href="{{ url(config('dashboard.uri') . '/setting/index') }}">
                    @lang('dashboard::setting.網站設定')
                </a>
            </li>
            <li class="breadcrumb-item" aria-current="page">
                <a href="#">
                    @if ($setting_id)
                        @if (isset($duplicate) && $duplicate)
                            @lang('dashboard::backend.複製')
                        @else
                            @lang('dashboard::backend.編輯')
                        @endif
                    @else
                        @lang('dashboard::backend.新增')
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

@section('js')
    @include('dashboard::laravel-filemanager')
@endsection

@section('main_block')
    @component('dashboard::' . config('backend.template') . '.components.backend-update-card')
        <div class="form-body">
            @include('dashboard::select', ['input_setting' => [
                'display_name' => 'model',
                'input_name' => 'model',
                'input_value' => old('model', optional($setting)->model),
                'option' => config('backend.setting.model'),
                'attribute' => 'required',
            ]])

            @include('dashboard::select', ['input_setting' => [
                'display_name' => 'type',
                'input_name' => 'type',
                'input_value' => old('type', optional($setting)->type),
                'option' => config('backend.setting.type'),
                'attribute' => 'required',
            ]])

            @include('dashboard::text', ['input_setting' => [
                'display_name' => __('dashboard::backend.標題'),
                'input_name' => 'title',
                'input_value' => old('title', optional($setting)->title),
                'attribute' => 'required',
            ]])

            @include('dashboard::textarea', ['input_setting' => [
                'display_name' => __('dashboard::backend.說明'),
                'input_name' => 'description',
                'input_value' => old('description', optional($setting)->description),
            ]])

            @include('dashboard::text', ['input_setting' => [
                'display_name' => 'setting_key',
                'input_name' => 'setting_key',
                'input_value' => old('setting_key', optional($setting)->setting_key),
                'attribute' => 'required',
            ]])

            @if ($setting_id > 0)
                @switch($setting->type)
                    @case('num')
                    @case('number')

                        {{-- 數字 --}}
                        @include('dashboard::number', ['input_setting' => [
                            'display_name' => 'setting_value',
                            'input_name' => 'setting_value',
                            'input_value' => old('setting_value', optional($setting)->setting_value),
                        ]])
                        @break

                    @case('str')
                    @case('string')
                    @case('text')

                        {{-- 文字 --}}
                        @include('dashboard::text', ['input_setting' => [
                            'display_name' => 'setting_value',
                            'input_name' => 'setting_value',
                            'input_value' => old('setting_value', optional($setting)->setting_value),
                        ]])
                        @break

                    @case('textarea')

                        {{-- 文字區 --}}
                        @include('dashboard::textarea', ['input_setting' => [
                            'display_name' => 'setting_value',
                            'input_name' => 'setting_value',
                            'input_value' => old('setting_value', optional($setting)->setting_value),
                        ]])
                        @break

                    @case('editor')

                        {{-- 編輯器 --}}
                        @include('dashboard::tinymce', ['input_setting' => [
                            'display_name' => 'setting_value',
                            'input_name' => 'setting_value',
                            'input_value' => old('setting_value', optional($setting)->setting_value),
                        ]])
                        @break

                    @case('file')
                    @case('file_name')

                        {{-- 檔案上傳 --}}
                        @include('dashboard::file', ['input_setting' => [
                            'display_name' => 'setting_value',
                            'input_name' => 'setting_value',
                            'input_value' => old('setting_value', optional($setting)->setting_value),
                        ]])
                        @break

                    @case('color')

                        {{-- 選取顏色 --}}
                        @include('dashboard::color', ['input_setting' => [
                            'display_name' => 'setting_value',
                            'input_name' => 'setting_value',
                            'input_value' => old('setting_value', optional($setting)->setting_value),
                        ]])
                        @break
                @endswitch
            @else
                @include('dashboard::value', ['input_setting' => [
                    'display_name' => 'setting_value',
                    'input_name' => 'setting_value',
                    'input_value' => '新增後再設定設定值',
                ]])
            @endif

            @include('dashboard::number', ['input_setting' => [
                'display_name' => __('dashboard::backend.排序'),
                'input_name' => 'sort',
                'input_value' => old('sort', optional($setting)->sort),
            ]])
        </div>
    @endcomponent
@endsection