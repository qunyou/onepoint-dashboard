@extends('dashboard::layouts.dashboard')

@section('title', config('site.name'))

@section('page-header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item" aria-current="page">
                <a href="{{ url(config('dashboard.uri') . '/dashboard/index') }}">
                    Home
                </a>
            </li>
        </ol>
    </nav>
@endsection

@section('main_block')
    <div class="card-update">
        <div class="row">
            <div class="col-md-2">
                <div class="form-body text-center pt-4">
                    <a href="{{ url(config('dashboard.uri') . '/blog/index') }}">
                        <i class="fas fa-blog fa-3x"></i>
                        <p class="mt-3">部落格文章數量</p>
                    </a>
                    <h1>{{ $blog_count ?? 0 }}</h1>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-body text-center pt-4">
                    <a href="{{ url(config('dashboard.uri') . '/article/index') }}">
                        <i class="far fa-file-alt fa-3x"></i>
                        <p class="mt-3">文章數量</p>
                    </a>
                    <h1>{{ $article_count ?? 0 }}</h1>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-body text-center pt-4">
                    <a href="{{ url(config('dashboard.uri') . '/interior-design/index') }}">
                        <i class="fas fa-couch fa-3x"></i>
                        <p class="mt-3">設計案數量</p>
                    </a>
                    <h1>{{ $interior_design_count ?? 0 }}</h1>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-body text-center pt-4">
                    <a href="{{ url(config('dashboard.uri') . '/dashboard/browser-agent') }}">
                        <i class="fas fa-chart-bar fa-3x"></i>
                        <p class="mt-3">總瀏覽數</p>
                    </a>
                    <h1>{{ $browser_agent_count ?? 0 }}</h1>
                </div>
            </div>
        </div>
    </div>
@endsection
