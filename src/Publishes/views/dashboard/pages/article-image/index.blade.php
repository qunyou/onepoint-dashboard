@extends(config('backend.view_path') . '.layouts.' . config('backend.layout_file'))

@section('title', config('site.name'))

@section('page-header', $page_header)

@section('js')
    <script>
        @include($path_presenter->backend_view('partials.jquery_check_all'))
    </script>
@stop

@section('main_block')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col">
                            <h4 class="card-title">{{ $page_title }}</h4>
                        </div>
                        <div class="col">
                            <div class="float-right">
                                <div class="btn-group">
                                    @if (!$trashed)
                                        <a class="btn btn-outline-deep-purple waves-effect" href="{{ url($uri . 'index?trashed=true') }}">
                                            <i class="fa fa-fw fa-recycle"></i><span class="d-none d-md-inline">{{ trans('backend.資源回收') }}</span>
                                        </a>
                                        <a class="btn btn-outline-deep-purple waves-effect" href="{{ url(config('dashboard.uri') . '/article/index') }}"><i class="fa fa-fw fa-arrow-left"></i><span class="d-none d-md-inline">回文章列表</span></a>
                                        @if ($base_services->hasAccess('article_index.c'))
                                            <a class="btn btn-outline-deep-purple waves-effect" href="{{ url($uri . 'update?article_id=' . $article_id) }}">
                                                <i class="fa fa-fw fa-plus"></i><span class="d-none d-md-inline">新增文章圖片</span>
                                            </a>
                                        @endif
                                    @else
                                        <a class="btn btn-outline-deep-purple waves-effect" href="{{ url($uri . 'index') }}"><i class="fa fa-fw fa-arrow-left"></i><span class="d-none d-md-inline">{{ trans('backend.回列表') }}</span></a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($list)
                    <form action="" method="post" />
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th class="check_all_width d-none d-md-table-cell">
                                                <input type="checkbox" name="select_all" id="select_all" value="" data-toggle="tooltip" data-original-title="{{ trans('backend.全選') }}" />
                                            </th>
                                            <th scope="col">文章{{ trans('backend.圖片預覽') }}</th>
                                            <th scope="col" class="d-none d-md-table-cell">文章圖片名稱</th>
                                            <th scope="col" class="d-none d-md-table-cell">備註</th>
                                            <th scope="col" class="d-none d-md-table-cell">{{ trans('backend.狀態') }}</th>
                                            @if (!$trashed)
                                                <th scope="col"></th>
                                                <th scope="col" class="th_sort_width d-none d-md-table-cell">{{ trans('backend.排序') }}</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($list as $element)
                                            <tr{!! $element->status == '停用' ? ' class="table-dark"' : '' !!}>
                                                <td class="d-none d-md-table-cell">
                                                    <input type="checkbox" name="checked_id[]" class="checkbox" value="{{ $element->id }}" />
                                                </td>
                                                <td><img src="{{ url(config('frontend.upload_path') . 'thumb/' . $element->file_name) }}" class="responsive-image" alt="文章圖片>{{ trans('backend.預覽') }}<"></td>
                                                <td class="d-none d-md-table-cell">{{ $element->name }}</td>
                                                <td class="d-none d-md-table-cell">{{ $element->note }}</td>
                                                <td class="d-none d-md-table-cell">{{ $element->status }}</td>
                                                @if (!$trashed)
                                                    <td>
                                                        <div class="btn-group float-right">
                                                            <a class="btn btn-secondary" href="{{ url($uri . 'detail?article_id=' . $article_id . '&article_image_id=' . $element->id) }}">
                                                                <i class="fas fa-info fa-fw"></i><span class="d-none d-md-inline">{{ trans('backend.檢視') }}</span>
                                                            </a>
                                                            @if ($base_services->hasAccess('article_index.u'))
                                                                <a class="btn btn-secondary d-none d-md-inline" href="{{ url($uri . 'update?article_id=' . $article_id . '&article_image_id=' . $element->id) }}">
                                                                    <i class="fas fa-edit fa-fw"></i>{{ trans('backend.編輯') }}
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td class="d-none d-md-table-cell">
                                                        <input type="text" name="sort[{{ $element->id }}]" class="form-control" value="{{ $element->sort }}" />
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <footer class="card-footer">
                            <div class="row">
                                <div class="col d-none d-md-inline">
                                    <p>{{ trans('backend.選取項目') }}</p>
                                    <div class="btn-group">
                                        @if ($trashed)
                                            <button type="submit" name="force_delete" value="force_delete" class="btn btn-secondary"><i class="fa fa-fw fa-trash"></i><span class="d-none d-md-inline">{{ trans('backend.永久刪除') }}</span></button>
                                            <button type="submit" name="restore" value="restore" class="btn btn-secondary"><i class="fa fa-fw fa-recycle"></i><span class="d-none d-md-inline">{{ trans('backend.還原') }}</span></button>
                                        @else
                                            <button type="submit" name="delete" value="delete" class="btn btn-secondary"><i class="fa fa-fw fa-trash"></i><span class="d-none d-md-inline">{{ trans('backend.刪除') }}</span></button>

                                            <button type="submit" name="status_enable" value="status_enable" class="btn btn-secondary"><i class="fas fa-eye fa-fw"></i> <span class="d-none d-md-inline">{{ trans('backend.設定前端顯示') }}</span></button>

                                            <button type="submit" name="status_disable" value="status_disable" class="btn btn-secondary"><i class="fas fa-eye-slash fa-fw"></i> <span class="d-none d-md-inline">{{ trans('backend.設定前端隱藏') }}</span></button>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-auto">
                                    {{-- {!! $list->appends($qs)->render() !!} --}}
                                    @include($path_presenter->backend_view('partials.pagination'), ['paginator' => $list])
                                    <div class="text-center">
                                        {{ trans('backend.共') }} {{ $list->total() }} {{ trans('backend.筆資料') }}
                                    </div>
                                </div>
                                <div class="col d-none d-md-inline">
                                    @if (!$trashed)
                                        <div class="btn-group float-right">
                                            <button type="submit" name="set_sort" value="set_sort" class="btn btn-secondary"><i class="fa fa-fw fa-sort"></i>{{ trans('backend.修改排序') }}</button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </footer>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection
