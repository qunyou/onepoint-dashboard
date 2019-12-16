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
                <a href="{{ url(config('dashboard.uri') . '/album-image/index') }}">
                    @lang('album.相片')
                </a>
            </li>
            <li class="breadcrumb-item" aria-current="page">
                <a href="#">
                    @if ($album_image_id)
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
    @component('dashboard::components.backend-update-card', $component_datas)
        @include('dashboard::backend-update-input', ['form_array' => $form_array_normal, 'form_value' => $album_image ?? ''])
    @endcomponent
@endsection
