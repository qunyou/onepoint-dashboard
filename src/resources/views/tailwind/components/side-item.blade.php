@if ($parent_permission)
    <button-collapse url="{{ $parent_url ?? false }}">
        <template v-slot:item_name>
            {!! $icon !!}
            <span class="ml-2">{!! $title !!}</span>
        </template>
        
        @if(count($sub_item))
            @foreach ($sub_item as $item)
                @if ($item['permission'])
                    <a href="{{ $item['url'] }}" class="text-gray-700 block px-4 py-2 text-sm" role="menuitem" tabindex="-1" id="options-menu-item-0">{{ $item['title'] }}</a>
                @endif
            @endforeach
        @endif
    </button-collapse>
@endif