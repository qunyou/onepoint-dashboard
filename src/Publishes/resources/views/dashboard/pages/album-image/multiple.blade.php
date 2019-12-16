@extends(config('backend.view_path') . '.layouts.' . config('backend.view_path'))

@section('title', config('site.name'))

@section('page-header', $page_header)

@section('css')
    @parent
    @include('shared.fine-uploader-template')
@endsection

@section('bottom')
    <script>
        var manualUploader = new qq.FineUploader({
            element: document.getElementById('fine-uploader-manual-trigger'),
            template: 'qq-template-manual-trigger',
            request: {
                endpoint: '{{ url($uri . 'multiple?album_id=' . $album_id) }}'
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
                                        <a class="btn btn-outline-deep-purple waves-effect" href="{{ url($uri . 'index?album_id=' . $album_id) }}"><i class="fa fa-fw fa-arrow-left"></i>{{ trans('backend.回列表') }}</a>
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
