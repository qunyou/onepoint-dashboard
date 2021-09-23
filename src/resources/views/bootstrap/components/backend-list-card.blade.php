<div class="card card-list">
    <div class="card-header">
        <div class="row justify-content-between">
            <div class="col-5 col-md-auto">
                <div class="card-title">
                    {{ $page_title }}
                    @if ($version)
                        - @lang('backend.版本檢視')
                    @endif
                </div>
            </div>
            <div class="col-7 col-md-auto">
                @if (!$trashed && !$version)
                    @if (isset($add_url) || isset($dropdown_items) || isset($button_block))
                        @component('dashboard::' . config('backend.template') .  '.components.dropdown-toggle', $dropdown_items)
                            {{ $button_block ?? '' }}
                            @if (isset($add_url))
                                <a class="btn btn-outline-deep-purple waves-effect" href="{{ $add_url }}">
                                    <i class="fa fa-plus"></i>@lang('backend.新增')
                                </a>
                            @endif
                        @endcomponent
                    @endif
                @else
                    <a class="btn btn-outline-deep-purple waves-effect float-right d-none d-md-inline" href="{{ $back_url }}">
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