<div class="card-update">
    <div class="row justify-content-between px-3">
        <div class="col-12">
            <div class="card-title">{{ $page_title }}</div>
        </div>
        <div class="col-md-12 top-btn-group">
            @component('dashboard::components.top-btn-group', $dropdown_items)
                {{ $button_block ?? '' }}
                <a class="btn btn-outline-deep-purple waves-effect d-xs-block" href="{{ $back_url }}">
                    <i class="fa fa-fw fa-arrow-left"></i>@lang('backend.回列表')
                </a>
            @endcomponent
        </div>
    </div>
    <div class="form-body">
        {{ $slot }}
    </div>
    <footer>
        <div class="row">
            <div class="col-md-12 top-btn-group">
                <a class="btn btn-outline-deep-purple waves-effect d-xs-block" href="{{ $back_url }}">
                    <i class="fa fa-fw fa-arrow-left"></i>@lang('backend.回列表')
                </a>
            </div>
        </div>
    </footer>
</div>