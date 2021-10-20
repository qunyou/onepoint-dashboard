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

<form id="form-submit" method="post" action="" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="card card-update">
        <div class="card-body">
            {{ $slot }}
        </div>
        @if (isset($footer_block))
            <div class="card-footer">
                {!! $footer_block !!}
            </div>
        @endif
    </div>
</form>
