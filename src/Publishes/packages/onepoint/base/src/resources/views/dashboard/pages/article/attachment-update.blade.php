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
                <a href="{{ url(config('dashboard.uri') . '/article/index') }}">
                    @lang('base::article.文章')
                </a>
            </li>
            <li class="breadcrumb-item" aria-current="page">
                <a href="{{ url(config('dashboard.uri') . '/article/update?article_id=' . $article_id) }}">
                    編輯
                </a>
            </li>
            <li class="breadcrumb-item" aria-current="page">
                <a href="{{ url($uri . 'update?' . $base_service->getQueryString(true, true) . '&tab=attachment') }}">
                    文章附檔列表
                </a>
            </li>
            <li class="breadcrumb-item" aria-current="page">
                <a href="#">
                    編輯文章附檔
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
    @component('dashboard::components.backend-update-card', $component_datas)
        @slot('top_btn')
            <a class="btn btn-outline-deep-purple waves-effect d-xs-block" href="{{ url($uri . 'update?' . $base_service->getQueryString(true, true) . '&tab=attachment') }}">
                <i class="fa fa-fw fa-arrow-left"></i> 文章附檔列表
            </a>
        @endslot
        <div class="form-body">    
            @include('dashboard::backend-update-input', ['form_array' => $form_array_normal, 'form_value' => $attachment ?? ''])
        </div>
    @endcomponent
@endsection
