<div class="btn-group {{ $btn_align ?? 'dropleft float-right' }}">
    {{-- 因為權限限制剩下一個選項時，只顯示單純按鈕，不顯示下拉選單 --}}
    @if (!empty($slot->toHtml()))
        {{ $slot }}
    @endif
    @isset($items)
        {{-- @if (count($items) != 1 && !empty($slot->toHtml()))) --}}
        @if (count($items) >= 1)
            @if (count($items) == 1 && empty($slot->toHtml()))
                @php
                    $link_class_string = 'btn btn-outline-deep-purple';
                @endphp
            @else
                <button type="button" class="btn btn-outline-deep-purple dropdown-toggle px-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="sr-only">Toggle Dropdown</span>
                </button>
                <div class="dropdown-menu">
                @php
                    $link_class_string = 'dropdown-item';
                @endphp
            @endif
        @else
            @php
                $link_class_string = 'btn btn-outline-deep-purple';
            @endphp
        @endif
        @foreach ($items as $key => $item)
            @switch($key)
                @case('檢視')
                    <a class="{{ $link_class_string }}" href="{{ $item['url'] }}">
                        <i class="fas fa-info"></i>@lang('backend.檢視')
                    </a>
                    @break
                @case('預覽')
                    <a class="{{ $link_class_string }}" href="{{ $item['url'] }}" target="_blank">
                        <i class="fa fa-eye"></i>@lang('backend.預覽')
                    </a>
                    @break
                @case('編輯')
                    <a class="{{ $link_class_string }}" href="{{ $item['url'] }}">
                        <i class="fas fa-edit"></i>@lang('backend.編輯')
                    </a>
                    @break
                @case('多檔上傳')
                    <a class="{{ $link_class_string }}" href="{{ $item['url'] }}">
                        <i class="fas fa-file-upload"></i>@lang('backend.多檔上傳')
                    </a>
                    @break
                @case('刪除')
                    <a class="{{ $link_class_string }}" href="{{ $item['url'] }}">
                        <i class="fas fa-trash-alt"></i>@lang('backend.刪除')
                    </a>
                    @break
                @case('複製')
                    <a class="{{ $link_class_string }}" href="{{ $item['url'] }}">
                        <i class="far fa-copy"></i>@lang('backend.複製')
                    </a>
                    @break
                @case('匯入')
                    <a class="{{ $link_class_string }}" href="{{ $item['url'] }}">
                        <i class="fas fa-file-excel"></i>@lang('backend.匯入')
                    </a>
                    @break
                @case('資源回收')
                    <a class="{{ $link_class_string }}" href="{{ $item['url'] }}">
                        <i class="fa fa-recycle"></i>@lang('backend.資源回收')
                    </a>
                    @break
                @case('版本')
                    <a class="{{ $link_class_string }}" href="{{ $item['url'] }}">
                        <i class="fas fa-code-branch"></i>@lang('backend.版本')
                    </a>
                    @break
                @case('使用此版本')
                    <a class="{{ $link_class_string }}" href="{{ $item['url'] }}">
                        <i class="fas fa-code-branch"></i>@lang('backend.使用此版本')
                    </a>
                    @break
                @case('關聯')
                    @if (is_array($item))
                        @foreach ($item as $item_key => $item_value)
                            <a class="{{ $link_class_string }}" href="{{ $item_value['url'] }}">
                                <i class="{{ $item_value['icon'] }}"></i>{{ $item_value['name'] }} <span class="badge badge-primary">{{ $item_value['with_count'] }}</span>
                            </a>
                        @endforeach
                    @else
                        <a class="{{ $link_class_string }}" href="{{ $item['url'] }}">
                            <i class="{{ $item['icon'] }}"></i>{{ $item['name'] }} <span class="badge badge-primary">{{ $item['with_count'] }}</span>
                        </a>
                    @endif
                    @break
                @default
                    <a class="{{ $link_class_string }}" href="{{ $item['url'] }}">
                        {!! $item['text'] ?? '' !!}
                    </a>
            @endswitch
        @endforeach
        @if (count($items) >= 1)
            @if (count($items) == 1 && empty($slot->toHtml()))
            @else
                </div>
            @endif
        @endif
    @endisset
</div>