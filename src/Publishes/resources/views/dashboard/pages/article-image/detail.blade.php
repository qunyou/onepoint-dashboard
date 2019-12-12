@extends(config('backend.view_path') . '.layouts.' . config('backend.layout_file'))

@section('title', config('site.name'))

@section('page-header', $page_header)

@section('js')
    @if (session('notify.message', false))
        <script>
            $(function(){
                $.notify({
                    message: '{{ session('notify.message') }}'
                },{
                    type: '{{ session('notify.type') }}'
                });
            });
        </script>
    @endif
@endsection

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
                                    <a class="btn btn-outline-deep-purple waves-effect" href="{{ url($uri . 'index?article_id=' . $article_id) }}"><i class="fa fa-fw fa-arrow-left"></i>{{ trans('backend.回列表') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @include('shared.value', ['display_name' => '圖片名稱', 'input_name' => 'name', 'input_value' => session('inputs.name', ''), 'error' => session('errors.name', false)])

                    @include('shared.value', ['display_name' => '圖片', 'value_type' => 'image', 'input_name' => 'file_name', 'input_value' => session('inputs.file_name', ''), 'error' => session('errors.file_name', false)])

                    @include('shared.value', ['display_name' => '備註', 'input_name' => 'note', 'input_value' => session('inputs.note', ''), 'error' => session('errors.note', false)])

                    @include('shared.value', ['display_name' => trans('backend.排序'), 'input_name' => 'sort', 'input_value' => session('inputs.sort', ''), 'error' => session('errors.sort', false)])

                    @include('shared.value', ['display_name' => trans('backend.狀態'), 'input_name' => 'status', 'input_value' => session('inputs.status', ''), 'error' => session('errors.status', false)])
                </div>
                <div class="card-footer">
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            功能
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="{{ url($uri . 'update?article_id=' . $article_id . '&article_image_id=' . $article_image_id) }}">修改</a>
                            <a class="dropdown-item" href="{{ url($uri . 'delete?article_id=' . $article_id . '&article_image_id=' . $article_image_id) }}">{{ trans('backend.刪除') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
