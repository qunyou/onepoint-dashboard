<!doctype html>
<html lang="{{ $backend_language }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="{{ config('backend.meta_keywords') }}">
        <meta name="description" content="{{ config('backend.meta_description') }}">
        <meta name="author" content="Onepoint">
        @if (config('backend.favicon', false))
            <link rel="shortcut icon" href="{{ url(config('backend.favicon')) }}" type="image/x-icon">
            <link rel="icon" href="{{ url(config('backend.favicon')) }}" type="image/x-icon">
        @endif
        @section('css')
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
            {{-- <link rel="stylesheet" href="{{ $path_presenter::backend_assets('css/uikit.min.css') }}" /> --}}
            {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit@3.7.6/dist/css/uikit.min.css" /> --}}
            {{-- <link rel="stylesheet" href="{{ $path_presenter::backend_assets('css/style.min.css?v=1.1.9') }}" /> --}}
            <link rel="stylesheet" href="/assets/dashboard/css/style.min.css?v=1.2.05" />
            {{-- <link rel="stylesheet" href="{{ $path_presenter::backend_assets('fontawesome/css/all.css') }}" /> --}}
            <script src="https://kit.fontawesome.com/70e57d8a62.js"></script>
        @show

        {{-- 網頁標題 --}}
        <title>{{ config('backend.html_page_title') }}</title>
    </head>
    <body>
        <div class="wrapper active" id="wrapper" data-color="{{ config('backend.sidebar.color', 'blue') }}">

            {{-- 邊欄背景圖 --}}
            <div class="sidebar" data-image="{{ url(config('backend.sidebar.img', 'assets/dashboard/img/sidebar-1.jpg')) }}">
                <div class="sidebar-wrapper">

                    {{-- 邊欄標題 --}}
                    <div class="logo">
                        <a href="{{ url(config('dashboard.uri') . '/dashboard/index') }}" class="simple-text">
                            @section('sidebar-header')
                                @lang('dashboard::backend.網站內容管理系統')
                            @show
                        </a>
                    </div>

                    {{-- 主導覽 --}}
                    <ul class="nav">
                        @component('dashboard::' . config('backend.template') . '.components.backend-sidebar', ['navigation_item' => $navigation_item])
                        @endcomponent
                    </ul>
                </div>
            </div>

            <div class="main-panel">
                <header class="top-bar bg-light pe-3 ps-5 py-3" color-on-scroll="500">
                    <div id="menu-toggle">
                        <i class="sidebarExpand fas fa-angle-double-left"></i>
                    </div>
                    <div class="row">
                        <div class="col-auto">

                            {{-- 麵包屑清單 --}}
                            @yield('page-header')
                        </div>
                        <div class="col-auto ms-auto d-none d-md-block">
                            <div class="row">
                                @section('top-item')

                                    {{-- 語言版本 --}}
                                    @if (config('backend.language', false))
                                        <div class="col-auto">
                                            <div class="dropdown">
                                                <a class="dropdown-toggle text-decoration-none" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                                    {{ config('backend.language')[config('app.locale')] ?? 'English' }}
                                                </a>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                    @foreach (config('backend.language') as $language_key => $language_item)
                                                        <a class="dropdown-item text-decoration-none" href="?lang={{ $language_key }}">{{ $language_item }}</a>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    @endif
                                    @if (config('app.url', false))
                                        <div class="col-auto">
                                            <a href="{{ config('frontend.url') }}" class="text-decoration-none" target="_blank"><i class="fas fa-desktop me-1"></i>@lang('dashboard::backend.檢視網站')</a>
                                        </div>
                                    @endif
                                @show
                                <div class="col-auto">
                                    <a href="{{ url(config('dashboard.uri') . '/logout') }}" class="text-decoration-none"><i class="fas fa-sign-out-alt me-1"></i>@lang('dashboard::auth.登出')</a>
                                </div>
                            </div>
                        </div>
                    </div>

                </header>

                <div class="content" id="app">
                    <div class="container-fluid">
                        @yield('main_block')
                    </div>
                </div>

                {{-- <footer class="footer">
                    <div class="copyright">
                        <div class="float-right">
                            <a href="#"><i class="fas fa-arrow-alt-circle-up fa-lg"></i></a>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </footer> --}}
            </div>
        </div>
        {{-- <script src="{{ $path_presenter::backend_assets('js/jquery-3.3.1.min.js') }}" type="text/javascript"></script> --}}
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
        {{-- <script src="{{ $path_presenter::backend_assets('js/popper.min.js') }}" type="text/javascript"></script> --}}
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
        {{-- <script src="{{ $path_presenter::backend_assets('js/bootstrap.min.js') }}" type="text/javascript"></script> --}}
        {{-- <script src="{{ $path_presenter::backend_assets('js/bootstrap-notify.js') }}" type="text/javascript"></script> --}}
        <script src="/assets/dashboard/js/bootstrap-notify.js" type="text/javascript"></script>
        {{-- <script src="{{ $path_presenter::backend_assets('js/uikit.min.js') }}" type="text/javascript"></script> --}}
        {{-- <script src="{{ $path_presenter::backend_assets('js/uikit-icons.min.js') }}" type="text/javascript"></script> --}}
        {{-- <script src="https://cdn.jsdelivr.net/npm/uikit@3.7.6/dist/js/uikit.min.js"></script> --}}
        {{-- <script src="https://cdn.jsdelivr.net/npm/uikit@3.7.6/dist/js/uikit-icons.min.js"></script> --}}
        {{-- <script src="{{ $path_presenter::backend_assets('js/dashboard.js?v=1.0.0') }}" type="text/javascript"></script> --}}
        <script src="/assets/dashboard/js/dashboard.js?v=1.0.0" type="text/javascript"></script>
        @if (isset($component_datas['use_drag_rearrange']) && $component_datas['use_drag_rearrange'])
            <script src="https://cdnjs.cloudflare.com/ajax/libs/TableDnD/0.9.1/jquery.tablednd.js" integrity="sha256-d3rtug+Hg1GZPB7Y/yTcRixO/wlI78+2m08tosoRn7A=" crossorigin="anonymous"></script>
        @endif
        <script src="/assets/dashboard/js/vue.min.js?v=2.6.10" type="text/javascript"></script>
        {{-- <script src="/assets/dashboard/js/moment.min.js" type="text/javascript"></script> --}}
        <script src="/assets/dashboard/js/axios.min.js" type="text/javascript"></script>
        @yield('js')
        <script>
            @if (isset($component_datas['use_drag_rearrange']) && $component_datas['use_drag_rearrange'])
                $(document).ready(function() {
                $("#row-table").tableDnD({
                dragHandle: ".drag",
                onDrop: function(table, row) {
                var rows = table.tBodies[0].rows;
                var new_sort = [];
                for (var i=0; i<rows.length; i++) { new_sort[i]=rows[i].id; } axios.get('{{ url($uri . 'drag-sort') }}?new_sort=' + new_sort.toString())
                            .then(function (response) {
                                console.log(response.data);
                            })
                            .catch(function (error) {
                                console.log(error);
                            });
                        },
                    });
                });
                @endif
    
            @if (session('notify.message', false))
                $(function(){
                    $.notify({
                        message: '{{ session('notify.message') }}'
                    },{
                        type: '{{ session('notify.type') }}'
                    });
                });
            @endif
    
            const app = new Vue({
                el: '#app',
                data: {
                    @yield('vuejs_data')
                    result: false
                },
                mounted: function() {
                    @yield('vuejs_mounted')
                },
                components: {
                    @yield('vuejs_components')
                },
                methods: {
                    @yield('vuejs_methods')
                },
                watch: {
                    @yield('vuejs_watchs')
                }
            })
        </script>
        @yield('bottom')
    </body>
</html>
