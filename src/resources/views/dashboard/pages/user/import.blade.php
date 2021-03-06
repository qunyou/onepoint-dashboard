@extends(config('backend.view_path') . '.layouts.' . config('backend.layout_file'))

@section('title', config('site.name'))

@section('page-header', $page_header)

@section('main_block')
    <form method="post" action="" enctype="multipart/form-data">
        {{ csrf_field() }}
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
                                        <a class="btn btn-outline-deep-purple waves-effect" href="{{ url($uri . 'index') }}"><i class="fa fa-fw fa-arrow-left"></i>{{ trans('backend.回列表') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @include('shared.file', ['display_name' => '上傳檔案', 'input_name' => 'file_name', 'input_value' => session('inputs.file_name', ''), 'multiple' => true, 'help' => '請使用正確格式之 CSV 檔', 'error' => session('errors.file_name', false)])
                    </div>
                    <footer class="card-footer">
                        <button type="submit" class="btn btn-outline-deep-purple waves-effect">{{ trans('backend.送出') }}</button>
                    </footer>
                </div>
                @if ($import_message)
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">匯入結果報告</h4>
                        </div>
                        <div class="card-body">
                            <ul>
                                @foreach ($import_message as $element)
                                    <li>{{ $element }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </form>
@endsection
