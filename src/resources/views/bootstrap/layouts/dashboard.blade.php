<!doctype html>
<html lang="{{ $backend_language ?? 'zh-tw' }}">
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
        @php
        $theme = request('theme', '');
        if (!empty($theme)) {
            session(['theme' => $theme]);
        }
        $theme = session('theme', config('backend.theme', 'default'));
        @endphp
        @section('css')
            <link rel="stylesheet" href="/assets/dashboard/css/bootstrap-{{ $theme }}.min.css?v=1.0.01" />
            <link rel="stylesheet" href="/assets/dashboard/css/style.min.css?v=1.2.18" />
            <script src="https://kit.fontawesome.com/70e57d8a62.js"></script>
        @show

        {{-- 網頁標題 --}}
        <title>{{ config('backend.html_page_title') }}</title>
    </head>
    <body>
        <div class="wrapper active {{ config('backend.sidebar.width_class', '') }}" id="wrapper">

            {{-- 邊欄背景圖 --}}
            <div class="sidebar bg-primary" style="z-index: 999;{!! config('backend.sidebar.img', '') !!}">
                <div class="sidebar-wrapper">

                    {{-- 邊欄標題 --}}
                    <div class="logo">
                        <a href="{{ url(config('dashboard.uri') . '/dashboard/index') }}">
                            @section('sidebar-header')
                                {{ config('app.name') }}
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
                <header class="top-bar border-bottom pe-3 ps-5 py-2" color-on-scroll="500">
                    <div id="menu-toggle" class="bg-primary">
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
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                    @foreach (config('backend.language') as $language_key => $language_item)
                                                        <a class="dropdown-item text-decoration-none" href="?lang={{ $language_key }}">{{ $language_item }}</a>
                                                    @endforeach
                                                </div>
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
                        <div class="px-2">
                            @yield('main_block')
                        </div>
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
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="/assets/dashboard/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="/assets/dashboard/js/popper.min.js"></script>
        <script src="/assets/dashboard/js/dashboard.js?v=1.0.01" type="text/javascript"></script>
        @if (isset($component_datas['use_drag_rearrange']) && $component_datas['use_drag_rearrange'])
            <script src="https://cdnjs.cloudflare.com/ajax/libs/TableDnD/0.9.1/jquery.tablednd.js" integrity="sha256-d3rtug+Hg1GZPB7Y/yTcRixO/wlI78+2m08tosoRn7A=" crossorigin="anonymous"></script>
        @endif
        <script src="/assets/dashboard/js/vue.min.js?v=2.6.11" type="text/javascript"></script>
        <script src="/assets/dashboard/js/axios.min.js" type="text/javascript"></script>
        @yield('js')
        <script>
            $(function() {
                @if (isset($component_datas['use_drag_rearrange']) && $component_datas['use_drag_rearrange'])
                    $("#row-table").tableDnD({
                        dragHandle: ".drag",
                        onDrop: function(table, row) {
                            var rows = table.tBodies[0].rows;
                            var new_sort = [];
                            for (var i=0; i<rows.length; i++) {
                                new_sort[i]=rows[i].id;
                            }
                            axios.get('{{ url($uri . 'drag-sort') }}?new_sort=' + new_sort.toString())
                                .then(function (response) {
                                    // console.log(response.data);
                                })
                                .catch(function (error) {
                                    console.log(error);
                                });
                        }
                    });
                @endif
                @if (session('notify.message', false))
                    var toastLive = document.getElementById('liveToast')
                    var toast = new bootstrap.Toast(toastLive)
                    toast.show()
                @endif
            });
    
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
        @if (session('notify.message', false))
            <div class="toast-container position-absolute top-0 end-0 p-3" style="z-index: 999;">
                <div id="liveToast" class="toast align-items-center text-white bg-primary border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            <i class="fas fa-info-circle"></i> {{ session('notify.message') }}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            </div>
        @endif
        @yield('bottom')
    </body>
</html>
