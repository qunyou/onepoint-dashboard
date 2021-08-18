{{-- 
/**
 * 文章
 * 1.0.01
 * packages/onepoint/base/src/resources/views/dashboard/pages/article/attachment-update.blade.php
 */
--}}
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
                    @lang('base::article.文章列表')
                </a>
            </li>
            <li class="breadcrumb-item" aria-current="page">
                <a href="{{ url(config('dashboard.uri') . '/article/update?article_id=' . $article_id) }}">
                    @lang('base::article.編輯文章')
                </a>
            </li>
            <li class="breadcrumb-item" aria-current="page">
                <a href="{{ url($uri . 'update?' . $base_service->getQueryString(true, true) . '&tab=attachment') }}">
                    @lang('base::article.文章附檔列表')
                </a>
            </li>
            <li class="breadcrumb-item" aria-current="page">
                <a href="#">
                    @lang('base::article.編輯文章附檔')
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
    @component('dashboard::components.backend-update-card', $component_datas)
        @slot('top_btn')
            <a class="btn btn-outline-deep-purple waves-effect d-xs-block" href="{{ url($uri . 'update?' . $base_service->getQueryString(true, true) . '&tab=attachment') }}">
                <i class="fa fa-fw fa-arrow-left"></i> @lang('base::article.文章附檔列表')
            </a>
        @endslot
        <div class="form-body">    
            @include('dashboard::backend-update-input', ['form_array' => $form_array_normal, 'form_value' => $attachment ?? ''])
        </div>
    @endcomponent
@endsection
