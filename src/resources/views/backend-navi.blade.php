@inject('base_service','Onepoint\Dashboard\Services\BaseService')

@if ($parent_permission)
    <li class="nav-item {{ $active }}">
        <a class="nav-link {{ count($sub_item) ? 'has-sub-caret' : '' }}" href="{{ count($sub_item) ? '#' . $base_service->slug($title, '-') : $url }}" @if(count($sub_item)) data-toggle="collapse" aria-expanded="false" aria-controls="{{ $title }}" @endif>
            <i class="{{ $icon }}" aria-hidden="true"></i>
            <span>{{ $title }}</span>
        </a>
        @if(count($sub_item))
            <div class="collapse {{ $parent_show_string }}" id="{{ $base_service->slug($title, '-') }}">
                <ul class="nav">
                    @foreach ($sub_item as $item)
                        @if ($item['permission'])
                            <li class="nav-item {{ $item['active'] }}">
                                <a class="nav-link sub_item" href="{{ $item['url'] }}">
                                    {{ $item['title'] }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        @endif
    </li>
@endif