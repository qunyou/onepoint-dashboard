<div class="card card-list">
    <div class="card-header">
        <div class="row justify-content-between">
            <div class="col-auto">
                <div class="card-title">{{ $page_title }}</div>
            </div>
            <div class="col-auto">
                @if (!$trashed)
                    @component('shared.components.dropdown-toggle', $dropdown_items)
                        {{ $button_block ?? '' }}
                        @if (isset($add_url))
                            <a class="btn btn-outline-deep-purple waves-effect" href="{{ $add_url }}">
                                <i class="fa fa-plus"></i>@lang('backend.新增')
                            </a>
                        @endif
                    @endcomponent
                @else
                    <a class="btn btn-outline-deep-purple waves-effect float-right" href="{{ $back_url }}">
                        <i class="fa fa-arrow-left"></i>@lang('backend.回列表')
                    </a>
                @endif
            </div>
        </div>
    </div>

    {{ $search_block ?? '' }}
    
    <form action="" method="post">
        @csrf
        @method('PUT')
        {{ $slot }}
    </form>
</div>