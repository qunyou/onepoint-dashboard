<div class="card-update">
    <!-- <div class="card-header"> -->
        <div class="row">
            <div class="col">
                <div class="card-title">{{ $page_title }}</div>
            </div>
            <div class="col">
                <div class="float-right">
                    <div class="btn-group">
                        <a class="btn btn-outline-deep-purple waves-effect" href="{{ $back_url }}">
                            <i class="fa fa-fw fa-arrow-left"></i><span class="d-none d-md-inline">@lang('backend.回列表')</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <!-- </div> -->
    <div class="form-body">
        {{ $slot }}
    </div>
    <footer>
        @component('dashboard::components.dropdown-toggle', $dropdown_items)
            <a class="btn btn-outline-deep-purple waves-effect" href="{{ $back_url }}">
                <i class="fa fa-fw fa-arrow-left"></i><span class="d-none d-md-inline">@lang('backend.回列表')</span>
            </a>
        @endcomponent
    </footer>
</div>