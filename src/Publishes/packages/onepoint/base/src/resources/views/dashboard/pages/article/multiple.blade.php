@inject('path_presenter', 'Onepoint\Dashboard\Presenters\PathPresenter')

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
                <a href="{{ url(config('dashboard.uri') . '/article/index?' . $base_service->getQueryString(true, true, ['article_id'])) }}">
                    @lang('base::article.文章列表')
                </a>
            </li>
            <li class="breadcrumb-item" aria-current="page">
                <a href="{{ url(config('dashboard.uri') . '/article/update?article_id=' . $article_id . '&' . $base_service->getQueryString(true, true, ['article_id'])) }}">
                    @lang('base::article.文章編輯')
                </a>
            </li>
            <li class="breadcrumb-item" aria-current="page">
                <a href="{{ url(config('dashboard.uri') . '/article/update?article_id=' . $article_id . '&' . $base_service->getQueryString(true, true, ['article_id']) . '&tab=image') }}">
                    @lang('base::article.文章圖片')
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

@section('css')
    @parent
    @include('dashboard::fine-uploader-template')
@endsection

@section('bottom')
    <script>
        var manualUploader = new qq.FineUploader({
            element: document.getElementById('fine-uploader-manual-trigger'),
            template: 'qq-template-manual-trigger',
            request: {
                endpoint: '{{ url($uri . 'multiple?article_id=' . $article_id) }}'
            },
            thumbnails: {
                placeholders: {
                    waitingPath: '{{ $path_presenter::backend_assets('fine-uploader/placeholders/waiting-generic.png') }}',
                    notAvailablePath: '{{ $path_presenter::backend_assets('fine-uploader/placeholders/not_available-generic.png') }}'
                }
            },
            validation: {
                allowedExtensions: [ "jpeg", "jpg", "gif", "png" ]
            },
            autoUpload: false,
            debug: false
        });
        manualUploader.setParams({
            _token: '{{ csrf_token() }}'
        });
        qq(document.getElementById("trigger-upload")).attach("click", function() {
            manualUploader.uploadStoredFiles();
        });
    </script>
@endsection

@section('main_block')
    <form method="post" action="" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-12">
                <div class="card card-update">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <div class="card-title">{{ $page_title }}</div>
                            </div>
                            <div class="col">
                                <div class="float-right">
                                    <div class="btn-group">
                                        <a class="btn btn-outline-deep-purple waves-effect" href="{{ url($uri . 'index?' . $base_service->getQueryString(true, true, ['tab'])) }}"><i class="fa fa-fw fa-arrow-left"></i>@lang('base::article.文章列表')</a>
                                        <a class="btn btn-outline-deep-purple waves-effect" href="{{ url($uri . 'update?' . $base_service->getQueryString(true, true, ['tab']) . '&tab=image') }}"><i class="fa fa-fw fa-arrow-left"></i>{{ trans('base::article.圖片列表') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Fine Uploader DOM Element
                        ====================================================================== -->
                        <div id="fine-uploader-manual-trigger"></div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
