{{-- 
/**
 * 文章
 * 1.0.01
 * packages/onepoint/base/src/resources/views/dashboard/pages/article/index.blade.php
 */
--}}
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
            @if (auth()->user()->hasAccess(['create-Onepoint\Base\Controllers\ArticleController']))
                <a href="{{ url($uri . 'update?' . $base_service->getQueryString(true, true, ['article_id'])) }}" class="btn btn-outline-deep-purple waves-effect d-xs-block">
                    <i class="fa fa-plus"></i>@lang('base::article.新增文章')
                </a>
            @endif
        @endslot
        @slot('search_block')
            <div class="card-update collapse search show" id="collapseGuide">
                <div class="form-body p-3">
                    <form action="" method="GET">
                        <div class="row">
                            <div class="col-auto">
                                @lang('base::article.文章標題')
                                <input type="text" name="q_article_title" class="form-control" value="{{ request('q_article_title', '') }}" placeholder="@lang('base::article.文章標題')">
                            </div>
                            <div class="col-auto">
                                @lang('base::article.文章內容')
                                <input type="text" name="q_article_content" class="form-control" value="{{ request('q_article_content', '') }}" placeholder="@lang('base::article.文章內容')">
                            </div>
                            @if ($category_select_item)
                                <div class="col-auto">
                                    @lang('base::article.文章分類')
                                    <select name="q_article_category_id" id="q_article_category_id" class="form-control">
                                        <option value="">不限</option>
                                        @foreach ($category_select_item as $category_id => $category_name)
                                            <option value="{{ $category_id }}" {{ request('q_article_category_id', 0) == $category_id ? 'selected' : '' }}>{{ $category_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                        </div>

                        <div class="text-center mt-2">
                            <div class="btn-group">
                                <a class="btn btn-outline-deep-purple waves-effect" data-toggle="collapse" href="#collapseGuide" role="button" aria-expanded="false" aria-controls="collapseGuide">
                                    @lang('dashboard::backend.關閉')
                                </a>
                                <a class="btn btn-outline-deep-purple waves-effect" href="{{ url($uri . 'index') }}">
                                    @lang('dashboard::backend.重設查詢')
                                </a>
                                <button type="submit" class="btn btn-outline-deep-purple waves-effect">
                                    @lang('dashboard::backend.查詢')
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endslot
    @endcomponent
@endsection
