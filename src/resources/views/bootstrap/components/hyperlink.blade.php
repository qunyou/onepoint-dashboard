@if (!empty($url))
    <a href="{{ $url }}" {{ $attr ?? '' }} {{ optional($link_target) == '另開分頁' ? 'target="_blank"' : '' }}>
        {{ $slot }}
    </a>
@else
    {{ $slot }}
@endif

{{-- @if (blank($outbound))
    <a href="{{ $url }}" {{ $attr or '' }}>{{ $slot }}</a>
@else
    <a href="{{ $outbound }}" {{ $attr or '' }} target="_blank">{{ $slot }}</a>
@endif --}}