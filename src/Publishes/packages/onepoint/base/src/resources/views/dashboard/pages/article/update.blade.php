{{-- 
/**
 * 文章
 * 1.0.01
 * packages/onepoint/base/src/resources/views/dashboard/pages/article/update.blade.php
 */
--}}
@inject('image_service', 'Onepoint\Dashboard\Services\ImageService')
@inject('file_service', 'Onepoint\Dashboard\Services\FileService')

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
                    @lang('base::article.文章列表')
                </a>
            </li>
            <li class="breadcrumb-item" aria-current="page">
                <a href="#">
                    @if (isset($duplicate) && $duplicate)
                        @lang('dashboard::backend.複製')
                    @else
                        @lang('base::article.編輯文章')
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
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script type="text/javascript">
    $(document).ready(function(){
        $('.reorder_link').on('click',function() {
            var queryParams = new URLSearchParams(window.location.search);
            queryParams.set("tab", "image");
            history.replaceState(null, null, "?"+queryParams.toString());

            $(".reorder-photos-list").sortable({ tolerance: 'pointer' });
            $('.reorder_link').html('@lang('dashboard::backend.儲存排序')');
            $('.reorder_link').attr("id","saveReorder");
            $('#reorderHelper').slideDown('slow');
            $('.image_link').attr("href","javascript:void(0);");
            $('.image_link').css("cursor","move");
            $("#saveReorder").click(function( e ) {
                if( !$("#saveReorder i").length ) {
                    $(".reorder-photos-list").sortable('destroy');
                    $("#reorderHelper").html("@lang('dashboard::backend.排序儲存中')");
                    var h = [];
                    $(".reorder-photos-list .col-auto").each(function() {
                        h.push($(this).attr('id').substr(9));
                    });
                    axios.post('{{ url($uri . 'image-sort?' . $base_service->getQueryString(true, true)) }}', {ids: " " + h + ""})
                    .then(function (response) {
                        if (response.status == 200) {
                            $("#reorderHelper").html("@lang('dashboard::backend.排序儲存完成')")
                            $('#reorderHelper').hide('slow');
                            window.location.reload();
                        }
                    })
                    .catch(function (error) {
                        console.log('axios fail');
                    });
                    return false;
                }	
                e.preventDefault();
            });
        });
    });
    @if (!empty($tab))
        $('#myTab a[href="#{{ $tab }}"]').tab('show');
    @endif
    </script>
@endsection

@section('main_block')
    @component('dashboard::components.backend-update-card', $component_datas)
        @slot('page_title')
            @if (isset($duplicate))
                @lang('dashboard::backend.複製')
            @else
                @lang('base::article.編輯文章')
            @endif
        @endslot
        @slot('top_btn')
            <a class="btn btn-outline-deep-purple waves-effect d-xs-block" href="{{ url($uri . 'index?' . $base_service->getQueryString(true, true, ['article_id'])) }}">
                <i class="fa fa-fw fa-arrow-left"></i>@lang('base::article.文章列表')
            </a>
        @endslot
        <div class="form-body">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link {{ $tab == 'normal' ? 'show active' : '' }}" id="normal-tab" data-toggle="tab" href="#normal" role="tab" aria-controls="normal">@lang('dashboard::backend.資料設定')</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $tab == 'seo' ? 'show active' : '' }}" id="seo-tab" data-toggle="tab" href="#seo" role="tab" aria-controls="seo">@lang('dashboard::backend.社群及SEO')</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $tab == 'image' ? 'show active' : '' }}" id="image-tab" data-toggle="tab" href="#image" role="tab" aria-controls="image">@lang('base::article.文章圖片') <span class="badge badge-pill badge-info">{{ $article ? $article->image->count() : '' }}</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $tab == 'attachment' ? 'show active' : '' }}" id="attachment-tab" data-toggle="tab" href="#attachment" role="tab" aria-controls="attachment">@lang('base::article.文章附檔') <span class="badge badge-pill badge-info">{{ $article ? $article->attachment->count() : '' }}</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $tab == 'advanced' ? 'show active' : '' }}" id="advanced-tab" data-toggle="tab" href="#advanced" role="tab" aria-controls="advanced">@lang('dashboard::backend.進階設定')</a>
                </li>
            </ul>
            <div class="tab-content pt-3" id="myTabContent">
                <div class="tab-pane fade {{ $tab == 'normal' ? 'show active' : '' }}" id="normal" role="tabpanel" aria-labelledby="normal-tab">
                    @include('dashboard::backend-update-input', ['form_array' => $form_array_normal, 'form_value' => $article ?? ''])
                    <button id="form-button" type="submit" class="btn btn-outline-deep-purple waves-effect">
                        @lang('dashboard::backend.送出')
                    </button>
                </div>
                <div class="tab-pane fade {{ $tab == 'seo' ? 'show active' : '' }}" id="seo" role="tabpanel" aria-labelledby="seo-tab">
                    @include('dashboard::backend-update-input', ['form_array' => $form_array_seo, 'form_value' => $article ?? ''])
                    <button id="form-button" type="submit" class="btn btn-outline-deep-purple waves-effect" name="tab" value="seo">
                        @lang('dashboard::backend.送出')
                    </button>
                </div>
                <div class="tab-pane fade {{ $tab == 'image' ? 'show active' : '' }}" id="image" role="tabpanel" aria-labelledby="image-tab">
                    <p>
                        <a href="{{ url($uri . 'multiple?' . $base_service->getQueryString(true, true) . '&tab=image') }}" class="btn btn-outline-deep-purple waves-effect">@lang('dashboard::backend.上傳圖片')</a>
                        @if ($article && $article->image->count())
                        <a href="javascript:void(0);" class="reorder_link btn btn-outline-deep-purple waves-effect" id="saveReorder">@lang('dashboard::backend.圖片排序')</a>
                        @endif
                    </p>
                    @if ($article && $article->image->count())
                    <div id="reorderHelper" class="alert alert-info" style="display:none;" role="alert">1. @lang('dashboard::backend.拖曳圖片重新調整順序')<br>2. @lang('dashboard::backend.按下儲存排序結果記錄新排序')</div>
                        <div class="row reorder-photos-list">
                            @foreach ($article->image as $image)
                                <div id="image_li_{{$image->id}}" class="ui-sortable-handle col-auto" style="height: 180px; overflow: hidden;">
                                    <a href="javascript:void(0);" class="image_link">
                                        {!! $image_service->thumb($image->file_name, ['style' => 'max-width: 120px;', 'class' => 'img-thumbnail'], '', 'article') !!}
                                    </a>
                                    <p class="text-center mt-1" style="margin-top: 1rem;">
                                        <a href="{{ url($uri . 'delete-image/' . $image->id . '?' . $base_service->getQueryString(true, true)) }}" class="btn btn-outline-deep-purple waves-effect">@lang('dashboard::backend.刪除')</a>
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="tab-pane fade {{ $tab == 'attachment' ? 'show active' : '' }}" id="attachment" role="tabpanel" aria-labelledby="attachment-tab">
                    @if ($article && $article->attachment->count())
                        <table class="table">
                            <tr>
                                <th>@lang('base::article.附檔檔名')</th>
                                <th>@lang('base::article.檔案大小')</th>
                                <th>@lang('base::article.附檔標題')</th>
                                <th></th>
                            </tr>
                            @foreach ($article->attachment as $key => $article_attachment)
                                <tr>
                                    <td>
                                        {{ $article_attachment->origin_name }}
                                    </td>
                                    <td>{{ $file_service->formatSizeUnits($article_attachment->file_size) }}</td>
                                    <td>{{ $article_attachment->attachment_title }}</td>
                                    <td class="text-right">
                                        {{--
                                            asset('storage/' . config('frontend.upload_path') . '/article/' . $article_attachment->file_name)
                                        --}}
                                        <a href="{{ url($uri . 'attachment-download/' . $article_attachment->id) }}" class="btn btn-outline-deep-purple waves-effect">@lang('dashboard::backend.下載') <i class="fas fa-download"></i></a>
                                        <a href="{{ url($uri . 'attachment-update/' . $article_attachment->id . '?' . $base_service->getQueryString(true, true)) }}" class="btn btn-outline-deep-purple waves-effect">@lang('dashboard::backend.編輯')</a>
                                        <a href="{{ url($uri . 'attachment-delete/' . $article_attachment->id . '?' . $base_service->getQueryString(true, true)) }}" class="btn btn-outline-deep-purple waves-effect">@lang('dashboard::backend.刪除')</a>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    @endif
                    <p class="text-center"><a href="{{ url($uri . 'attachment-add?' . $base_service->getQueryString(true, true)) }}" class="btn btn-outline-deep-purple waves-effect">新增</a></p>
                </div>
                <div class="tab-pane fade {{ $tab == 'advanced' ? 'show active' : '' }}" id="advanced" role="tabpanel" aria-labelledby="advanced-tab">
                    @include('dashboard::backend-update-input', ['form_array' => $form_array_advanced, 'form_value' => $article ?? ''])
                    <button id="form-button" type="submit" class="btn btn-outline-deep-purple waves-effect" name="tab" value="advanced">
                        @lang('dashboard::backend.送出')
                    </button>
                </div>
            </div>
        </div>
    @endcomponent
@endsection
