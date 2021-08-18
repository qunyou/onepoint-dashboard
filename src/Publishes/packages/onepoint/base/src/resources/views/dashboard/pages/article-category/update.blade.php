{{-- 
/**
 * 文章分類
 * 1.0.01
 * packages/onepoint/base/src/resources/views/dashboard/pages/article-category/update.blade.php
 */
--}}
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
                <a href="{{ url(config('dashboard.uri') . '/article-category/index') }}">
                    @lang('base::article.文章分類')
                </a>
            </li>

            <li class="breadcrumb-item" aria-current="page">
                <a href="#">
                    @if ($article_category_id)
                        @if (isset($duplicate) && $duplicate)
                            @lang('dashboard::backend.複製')
                        @else
                            @lang('base::article.編輯文章分類')
                        @endif
                    @else
                        @lang('base::article.新增文章分類')
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

@section('main_block')
    @component('dashboard::components.backend-update-card')
        @slot('page_title')
            @if ($article_category_id)
                @if (isset($duplicate) && $duplicate)
                    @lang('dashboard::backend.複製')
                @else
                    @lang('base::article.編輯文章分類')
                @endif
            @else
                @lang('base::article.新增文章分類')
            @endif
        @endslot

        <div class="form-body">
            @slot('top_btn')
                <a class="btn btn-outline-deep-purple waves-effect d-xs-block" href="{{ url($uri . 'index?' . $base_service->getQueryString(true, true, ['article_category_id'])) }}">
                    <i class="fa fa-fw fa-arrow-left"></i>@lang('base::article.文章分類')
                </a>
            @endslot
            @include('dashboard::backend-update-input', ['form_array' => $form_array, 'form_value' => $article_category ?? ''])
        </div>
    @endcomponent
@endsection
