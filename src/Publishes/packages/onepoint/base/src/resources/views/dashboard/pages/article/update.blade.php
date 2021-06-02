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
                <a href="{{ url(config('dashboard.uri') . '/article/index') }}">
                    @lang('base::article.文章')
                </a>
            </li>
            <li class="breadcrumb-item" aria-current="page">
                <a href="#">
                    @if ($article_id)
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
    @component('dashboard::components.backend-update-card', $component_datas)
        @slot('top_btn')
            <a class="btn btn-outline-deep-purple waves-effect d-xs-block" href="{{ url($uri . 'index?' . $base_service->getQueryString(true, true, ['article_id'])) }}">
                <i class="fa fa-fw fa-arrow-left"></i>@lang('base::article.文章列表')
            </a>
        @endslot
        <div class="form-body">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="normal-tab" data-toggle="tab" href="#normal" role="tab" aria-controls="normal" aria-selected="true">@lang('dashboard::backend.資料設定')</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="advanced-tab" data-toggle="tab" href="#advanced" role="tab" aria-controls="advanced" aria-selected="false">@lang('dashboard::backend.進階設定')</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="seo-tab" data-toggle="tab" href="#seo" role="tab" aria-controls="seo" aria-selected="false">@lang('dashboard::backend.社群及SEO')</a>
                </li>
            </ul>
            <div class="tab-content pt-3" id="myTabContent">
                <div class="tab-pane fade show active" id="normal" role="tabpanel" aria-labelledby="normal-tab">
                    @include('dashboard::backend-update-input', ['form_array' => $form_array_normal, 'form_value' => $article ?? ''])
                </div>
                <div class="tab-pane fade" id="advanced" role="tabpanel" aria-labelledby="advanced-tab">
                    @include('dashboard::backend-update-input', ['form_array' => $form_array_advanced, 'form_value' => $article ?? ''])
                </div>
                <div class="tab-pane fade" id="seo" role="tabpanel" aria-labelledby="seo-tab">
                    @include('dashboard::backend-update-input', ['form_array' => $form_array_seo, 'form_value' => $article ?? ''])
                </div>
            </div>
        </div>
    @endcomponent
@endsection
