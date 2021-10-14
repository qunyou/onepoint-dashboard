@if (isset($page_title))
<div class="container-fluid">
    <div class="h4 py-3">
        {!! $page_title !!}
    </div>
</div>
@endif

@if (isset($button_block))
<div class="px-2 py-3 overflow-x">
    {!! $button_block !!}
</div>
@endif

<div class="card card-update">
    <div class="card-body">
        @component('dashboard::' . config('backend.template') .  '.components.top-btn-group', $dropdown_items ?? [])
        {{ $slot }}
    </div>
    {{-- <footer>
        <div class="row">
            <div class="col-md-12 top-btn-group">
                <a class="btn btn-outline-deep-purple waves-effect d-xs-block" href="{{ $back_url }}">
                    <i class="fa fa-fw fa-arrow-left"></i>@lang('dashboard::backend.回列表')
                </a>
            </div>
        </div>
    </footer> --}}
</div>