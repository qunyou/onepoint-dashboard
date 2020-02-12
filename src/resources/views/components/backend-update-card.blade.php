<form id="form-submit" method="post" action="" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="card-update">
        <div class="row">
            <div class="col">
                <div class="card-title">{{ $page_title }}</div>
            </div>
            <div class="col">
                @if ($back_url)
                    <div class="float-right">
                        <div class="btn-group">
                            <a class="btn btn-outline-deep-purple waves-effect" href="{{ $back_url }}">
                                <i class="fa fa-fw fa-arrow-left"></i><span class="d-none d-md-inline">@lang('backend.回列表')</span>
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        {{ $slot }}
        <footer>
            <button id="form-button" type="submit" class="btn btn-outline-deep-purple waves-effect">
                @lang('backend.送出')
            </button>
        </footer>
    </div>
</form>