@if (blank($outbound))
    <a href="{{ $url }}" {{ $attr or '' }}>{{ $slot }}</a>
@else
    <a href="{{ $outbound }}" {{ $attr or '' }} target="_blank">{{ $slot }}</a>
@endif

