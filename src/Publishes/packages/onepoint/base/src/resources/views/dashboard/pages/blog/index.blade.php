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
            {{-- <li class="breadcrumb-item" aria-current="page">
                <a href="{{ url(config('dashboard.uri') . '/blog-category/index') }}">
                    @lang('base::blog.文章分類')
                </a>
            </li> --}}
            <li class="breadcrumb-item" aria-current="page">
                <a href="{{ url(config('dashboard.uri') . '/blog/index') }}">
                    @lang('base::blog.文章列表')
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
        @slot('button_block')
            <a class="btn btn-outline-deep-purple waves-effect d-xs-block" href="{{ url(config('dashboard.uri') . '/blog-category/index') }}">
                <i class="fas fa-folder-open"></i>@lang('base::blog.部落格分類')
            </a>
            <a href="{{ url($uri . 'update?' . $base_service->getQueryString(true, true, ['blog_id'])) }}" class="btn btn-outline-deep-purple waves-effect d-xs-block">
                <i class="fa fa-plus"></i>新增
            </a>
        @endslot
    @endcomponent
@endsection