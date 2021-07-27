<form id="form-submit" method="post" action="" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="card-update">
        <div class="row justify-content-between px-3">
            @if (isset($page_title))
                <div class="col-12">
                    <div class="card-title">{{ $page_title }}</div>
                </div>
            @endif
            <div class="col-md-12 top-btn-group">
                {{ $top_btn ?? '' }}
            </div>
        </div>

        {{ $slot }}
        @if (isset($footer_hide) ? !$footer_hide : true)
            <footer>
                <button id="form-button" type="submit" class="btn btn-outline-deep-purple waves-effect">
                    @lang('dashboard::backend.送出')
                </button>
            </footer>
        @endif
    </div>
</form>
