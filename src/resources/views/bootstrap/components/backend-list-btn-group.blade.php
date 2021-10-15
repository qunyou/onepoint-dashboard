<div class="btn-group">
    {{-- 因為權限限制剩下一個選項時，只顯示單純按鈕，不顯示下拉選單 --}}
    @if (!empty($slot->toHtml()))
        {{ $slot }}
    @endif
    @isset($items)
        @php
            if (isset($status)) {
                $btn_class = $status == config('db_status_false_string') ? 'btn-secondary' : 'btn-primary';
            } else {
                $btn_class = 'btn-primary';
            }
            $link_class_string = 'btn ' . $btn_class;
        @endphp
        @foreach ($items as $key => $item)
            @switch($key)
                @case('檢視')
                    <a class="{{ $link_class_string }}" href="{{ $item['url'] }}">
                        <i class="fas fa-info"></i>@lang('dashboard::backend.檢視')
                    </a>
                    @break
                @case('預覽')
                    <a class="{{ $link_class_string }}" href="{{ $item['url'] }}" target="_blank">
                        <i class="fa fa-eye"></i>@lang('dashboard::backend.預覽')
                    </a>
                    @break
                @case('編輯')
                    <a class="{{ $link_class_string }}" href="{{ $item['url'] }}">
                        <i class="fas fa-edit"></i>@lang('dashboard::backend.編輯')
                    </a>
                    @break
                @case('多檔上傳')
                    <a class="{{ $link_class_string }}" href="{{ $item['url'] }}">
                        <i class="fas fa-file-upload"></i>@lang('dashboard::backend.多檔上傳')
                    </a>
                    @break
                @case('刪除')
                    <a class="{{ $link_class_string }}" href="{{ $item['url'] }}">
                        <i class="fas fa-trash-alt"></i>@lang('dashboard::backend.刪除')
                    </a>
                    @break
                @case('複製')
                    <a class="{{ $link_class_string }}" href="{{ $item['url'] }}">
                        <i class="far fa-copy"></i>@lang('dashboard::backend.複製')
                    </a>
                    @break
                @case('匯入')
                    <a class="{{ $link_class_string }}" href="{{ $item['url'] }}">
                        <i class="fas fa-file-excel"></i>@lang('dashboard::backend.匯入')
                    </a>
                    @break
                @case('資源回收')
                    <a class="{{ $link_class_string }}" href="{{ $item['url'] }}">
                        <i class="fa fa-recycle"></i>@lang('dashboard::backend.資源回收')
                    </a>
                    @break
                @case('版本')
                    <a class="{{ $link_class_string }}" href="{{ $item['url'] }}">
                        <i class="fas fa-code-branch"></i>@lang('dashboard::backend.版本')
                    </a>
                    @break
                @case('使用此版本')
                    <a class="{{ $link_class_string }}" href="{{ $item['url'] }}">
                        <i class="fas fa-code-branch"></i>@lang('dashboard::backend.使用此版本')
                    </a>
                    @break
                @case('關聯')
                    @if (is_array($item))
                        @foreach ($item as $item_key => $item_value)
                            <a class="{{ $link_class_string }}" href="{{ $item_value['url'] }}">
                                <i class="{{ $item_value['icon'] }}"></i>{{ $item_value['name'] }} 
                                {{-- <span class="badge bg-light text-dark">{{ $item_value['with_count'] }}</span> --}}
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ $item_value['with_count'] }}
                                    <span class="visually-hidden">records count</span>
                                </span>
                            </a>
                        @endforeach
                    @else
                        <a class="{{ $link_class_string }}" href="{{ $item['url'] }}">
                            <i class="{{ $item['icon'] }}"></i>{{ $item['name'] }} 
                            {{-- <span class="badge bg-light text-dark">{{ $item['with_count'] }}</span> --}}
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ $item['with_count'] }}
                                <span class="visually-hidden">records count</span>
                            </span>
                        </a>
                    @endif
                    @break
                @default
                    @foreach ($item as $item_key => $item_value)
                        <a class="{{ $link_class_string }}" href="{{ $item_value['url'] }}">
                            {!! $item_value['text'] ?? '' !!}
                        </a>
                    @endforeach
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
<div class="clearfix"></div>