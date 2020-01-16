<form id="form-submit" method="post" action="" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="card card-update">
        <div class="card-header">
            <div class="row">
                <div class="col">
                    <div class="card-title">{{ $page_title }}</div>
                </div>
                <div class="col">
                    @if ($back_url)
                        <div class="float-right">
                            <div class="btn-group">
                                <a class="btn btn-outline-deep-purple waves-effect" href="{{ $back_url }}">
                                    <i class="fa fa-fw fa-arrow-left"></i> @lang('backend.回列表')
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-body">
            {{ $slot }}
        </div>
        <footer class="card-footer">
            <button id="form-button" type="submit" class="btn btn-outline-deep-purple waves-effect">
                @lang('backend.送出')
            </button>
        </footer>
    </div>
</form>