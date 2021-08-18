{{-- 
/**
 * 文章
 * 1.0.01
 * packages/onepoint/base/src/resources/views/dashboard/pages/article/add.blade.php
 */
--}}
@inject('image_service', 'Onepoint\Dashboard\Services\ImageService')
@inject('file_service', 'Onepoint\Dashboard\Services\FileService')

@extends('dashboard::layouts.dashboard')

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
                <a href="{{ url(config('dashboard.uri') . '/article/index') }}">
                    @lang('base::article.文章列表')
                </a>
            </li>
            <li class="breadcrumb-item" aria-current="page">
                <a href="#">
                    @lang('base::article.新增文章')
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
    <script type="text/javascript">
    @if (!empty($tab))
        $('#myTab a[href="#{{ $tab }}"]').tab('show');
    @endif
    </script>
@endsection

@section('main_block')
    @component('dashboard::components.backend-update-card')
        @slot('page_title')
            @lang('base::article.新增文章')
        @endslot
        @slot('top_btn')
            <a class="btn btn-outline-deep-purple waves-effect d-xs-block" href="{{ url($uri . 'index?' . $base_service->getQueryString(true, true, ['article_id'])) }}">
                <i class="fa fa-fw fa-arrow-left"></i>@lang('base::article.文章列表')
            </a>
        @endslot
        <div class="form-body">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link {{ $tab == 'normal' ? 'show active' : '' }}" id="normal-tab" data-toggle="tab" href="#normal" role="tab" aria-controls="normal">@lang('dashboard::backend.資料設定')</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $tab == 'seo' ? 'show active' : '' }}" id="seo-tab" data-toggle="tab" href="#seo" role="tab" aria-controls="seo">@lang('dashboard::backend.社群及SEO')</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $tab == 'advanced' ? 'show active' : '' }}" id="advanced-tab" data-toggle="tab" href="#advanced" role="tab" aria-controls="advanced">@lang('dashboard::backend.進階設定')</a>
                </li>
            </ul>
            <div class="tab-content pt-3" id="myTabContent">
                <div class="tab-pane fade {{ $tab == 'normal' ? 'show active' : '' }}" id="normal" role="tabpanel" aria-labelledby="normal-tab">
                    @include('dashboard::backend-update-input', ['form_array' => $form_array_normal])
                    <button id="form-button" type="submit" class="btn btn-outline-deep-purple waves-effect">
                        @lang('dashboard::backend.送出')
                    </button>
                </div>
                <div class="tab-pane fade {{ $tab == 'seo' ? 'show active' : '' }}" id="seo" role="tabpanel" aria-labelledby="seo-tab">
                    @include('dashboard::backend-update-input', ['form_array' => $form_array_seo])
                    <button id="form-button" type="submit" class="btn btn-outline-deep-purple waves-effect" name="tab" value="seo">
                        @lang('dashboard::backend.送出')
                    </button>
                </div>
                <div class="tab-pane fade {{ $tab == 'advanced' ? 'show active' : '' }}" id="advanced" role="tabpanel" aria-labelledby="advanced-tab">
                    @include('dashboard::backend-update-input', ['form_array' => $form_array_advanced])
                    <button id="form-button" type="submit" class="btn btn-outline-deep-purple waves-effect" name="tab" value="advanced">
                        @lang('dashboard::backend.送出')
                    </button>
                </div>
            </div>
        </div>
    @endcomponent
@endsection
