<form id="form-submit" method="post" action="" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="card-update">
        <div class="row justify-content-between px-3">
            <div class="col-12">
                <div class="card-title">{{ $page_title }}</div>
            </div>
            <div class="col-md-12 top-btn-group">
                @if ($back_url)
                    <a class="btn btn-outline-deep-purple waves-effect d-xs-block" href="{{ $back_url }}">
                        <i class="fa fa-fw fa-arrow-left"></i>@lang('dashboard::backend.回列表')
                    </a>
                @endif
            </div>
        </div>
        {{ $slot }}
        <footer>
            <button id="form-button" type="submit" class="btn btn-outline-deep-purple waves-effect">
                @lang('dashboard::backend.送出')
            </button>
        </footer>
    </div>
</form>