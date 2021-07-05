@extends('dashboard::layouts.dashboard')

@section('top-item')
    @include('base::dashboard.includes.top-item')
    @parent
@endsection

@section('sidebar-header')
    @include('base::dashboard.includes.sidebar-header')
@endsection

@section('page-header')
    <nav class="mr-auto" aria-label="breadcrumb">
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
                @lang('base::article.文章列表')
            @else
                @lang('dashboard::backend.資源回收')
            @endif
        @endslot
        @slot('button_block')
            @if (auth()->user()->hasAccess(['read-Onepoint\Base\Controllers\ArticleCategoryController']))
            <a class="btn btn-outline-deep-purple waves-effect d-xs-block" href="{{ url(config('dashboard.uri') . '/article-category/index') }}">
                <i class="fas fa-folder-open"></i>@lang('base::article.文章分類')
            </a>
            @endif
        @endslot
    @endcomponent
@endsection