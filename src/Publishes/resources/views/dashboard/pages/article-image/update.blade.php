@extends(config('backend.view_path') . '.layouts.' . config('backend.layout_file'))

@section('title', config('site.name'))

@section('page-header', $page_header)

@section('main_block')
    <form method="post" action="" enctype="multipart/form-data">
        @csrf
        @method('PUT')
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
                        @include('shared.text', ['display_name' => '圖片名稱', 'input_name' => 'name', 'input_value' => session('inputs.name', ''), 'attribute' => 'required', 'help' => '主要提供後台辨識用，前台使用於alt圖片說明，供搜尋引擎收錄用，不顯示於前台畫面中', 'error' => session('errors.name', false)])

                        @include('shared.file', ['display_name' => '圖片', 'input_name' => 'file_name', 'input_value' => session('inputs.file_name', ''), 'help' => '選擇本地圖檔，可接受jpg、png、gif格式，檔案大小 2 MB以內之檔案', 'error' => session('errors.file_name', false)])

                        @include('shared.textarea', ['display_name' => '備註', 'input_name' => 'note', 'input_value' => session('inputs.note', ''), 'error' => session('errors.note', false)])

                        @include('shared.number', ['display_name' => trans('backend.排序'), 'input_name' => 'sort', 'input_value' => session('inputs.sort', ''), 'error' => session('errors.sort', false)])

                        @include('shared.select', ['display_name' => trans('backend.狀態'), 'input_name' => 'status', 'option' => config('site.status_item'), 'input_value' => session('inputs.status', ''), 'error' => session('errors.status', false)])
                    </div>
                    <footer class="card-footer">
                        <button type="submit" class="btn btn-outline-deep-purple waves-effect">{{ trans('backend.送出') }}</button>
                    </footer>
                </div>
            </div>
        </div>
    </form>
@endsection
