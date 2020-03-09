<meta charset="utf-8">
{{-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> --}}
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta http-equiv="content-type" content="text/html" />

<title>@yield('html_title')</title>
{{-- <meta http-equiv="x-ua-compatible" content="ie=edge"> --}}
<meta name="description" content="@yield('meta_description')">
<meta name="keywords" content="@yield('meta_keywords')">
<meta name="author" content="qun@onepoint.com.tw" />

@if (!empty($favicon))
    <link rel="shortcut icon" type="image/x-icon" href="{{ $path_presenter->upload($favicon) }}">
@endif

@if (!empty($og_title))
    <meta property="og:locale" content="zh-tw" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="{{ $og_title }}" />
    <meta property="og:description" content="{{ $og_description ?? '' }}" />
    <meta property="og:url" content="{{ url()->full() }}" />
    <meta property="og:site_name" content="{{ $web_name ?? '' }}" />
    @if (!empty($og_image))
        <meta property="og:image" content="{{ $path_presenter->upload($og_image) }}" />
    @endif
@endif
{{ $slot }}