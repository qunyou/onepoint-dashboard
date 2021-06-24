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
                    @lang('base::article.文章')
                </a>
            </li>
            <li class="breadcrumb-item" aria-current="page">
                <a href="#">
                    @lang('dashboard::backend.檢視')
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
    @component('dashboard::components.backend-detail-card', $component_datas)
        @slot('top_btn')
            <a class="btn btn-outline-deep-purple waves-effect d-xs-block" href="{{ url($uri . 'index?' . $base_service->getQueryString(true, true, ['article_id'])) }}">
                <i class="fa fa-fw fa-arrow-left"></i>@lang('base::article.文章列表')
            </a>
        @endslot
        @include('dashboard::backend-update-input', ['form_array' => $form_array, 'form_value' => $article ?? ''])
    @endcomponent
@endsection
