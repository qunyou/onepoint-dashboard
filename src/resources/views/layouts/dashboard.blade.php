@inject('path_presenter', 'Onepoint\Dashboard\Presenters\PathPresenter')

<!doctype html>
<html>

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />
    <meta name="keywords" content="{{ config('backend.meta_keywords') }}" />
    <meta name="description" content="{{ config('backend.meta_description') }}">
    <meta name="author" content="Onepoint">
    @if (config('backend.favicon', false))
        <link rel="shortcut icon" href="{{ url(config('backend.favicon')) }}" type="image/x-icon">
        <link rel="icon" href="{{ url(config('backend.favicon')) }}" type="image/x-icon">
    @endif
    @section('css')
        <link rel="stylesheet" href="{{ $path_presenter::backend_assets('css/uikit.min.css') }}" />
        <link rel="stylesheet" href="{{ $path_presenter::backend_assets('css/style.min.css?v=1.1.9') }}" />
        <link rel="stylesheet" href="{{ $path_presenter::backend_assets('fontawesome/css/all.css') }}" />
    @show

    {{-- 網頁標題 --}}
    <title>{{ config('backend.html_page_title') }}</title>
    <style>
        .input-require {
            color: #f00;
            font-size: 2rem;
            line-height: 1rem;
        }
        .btn-outline-deep-purple.active {
            background-color: #7752b3;
            color: #fff;
        }
        .move {cursor: move;}
    </style>
</head>

<body>
    <div class="wrapper active" id="wrapper" data-color="{{ config('backend.sidebar.color', 'purple') }}">
        
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
                    @component('dashboard::components.backend-sidebar', ['navigation_item' => $navigation_item])
                        {{-- <li class="nav-item">
                            <a class="nav-link" href="{{ url(config('dashboard.uri') . '/user/profile') }}">
                                <i class="fas fa-lock" aria-hidden="true"></i>
                                <span>@lang('dashboard::auth.修改密碼')</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url(config('dashboard.uri') . '/logout') }}">
                                <i class="fas fa-sign-out-alt" aria-hidden="true"></i>
                                <span>@lang('dashboard::auth.登出')</span>
                            </a>
                        </li>
                        @if (config('frontend.url', false))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ config('frontend.url') }}" target="_blank">
                                    <i class="fas fa-home" aria-hidden="true"></i>
                                    <span>@lang('dashboard::backend.檢視網站')</span>
                                </a>
                            </li>
                        @endif
                        --}}
                    @endcomponent
                </ul>
            </div>
        </div>

        <div class="main-panel">

            <div class="container-fluid">
                <nav class="navbar navbar-expand-lg navbar-light" color-on-scroll="500">
                    <div id="menu-toggle">
                        <i class="sidebarExpand fas fa-angle-double-left"></i>
                    </div>

                    {{-- 麵包屑清單 --}}
                    @yield('page-header')

                    <div class="nav-top ml-auto d-none d-md-block">
                        <ul class="list-group list-group-horizontal">
                            @section('top-item')
                                {{-- 語言版本 --}}
                                @if (config('backend.language', false))
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            {{ config('backend.language')[config('app.locale')] ?? 'English' }}
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                            @foreach (config('backend.language') as $language_key => $language_item)
                                                <a class="dropdown-item" href="?lang={{ $language_key }}">{{ $language_item }}</a>
                                            @endforeach
                                        </div>
                                    </li>
                                @endif
                                @if (config('app.url', false))
                                    <li>
                                        <a href="{{ config('frontend.url') }}" target="_blank"><i class="fas fa-home"></i>@lang('dashboard::backend.檢視網站')</a>
                                    </li>
                                @endif
                            @show
                            
                            {{--
                            <li>
                                <a href="{{ url(config('dashboard.uri') . '/user/profile') }}"><i class="fas fa-lock"></i>@lang('dashboard::auth.修改密碼')</a>
                            </li>
                            --}}
                            <li>
                                <a href="{{ url(config('dashboard.uri') . '/logout') }}"><i class="fas fa-sign-out-alt"></i>@lang('dashboard::auth.登出')</a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>

            <div class="content" id="app">
                <div class="container-fluid">
                    @yield('main_block')
                </div>
            </div>

            <footer class="footer">
                <div class="copyright">
                    <div class="float-right">
                        <a href="#"><i class="fas fa-arrow-alt-circle-up fa-lg"></i></a>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </footer>
        </div>
    </div>
</body>
<script src="{{ $path_presenter::backend_assets('js/jquery-3.3.1.min.js') }}" type="text/javascript"></script>
<script src="{{ $path_presenter::backend_assets('js/popper.min.js') }}" type="text/javascript"></script>
<script src="{{ $path_presenter::backend_assets('js/bootstrap.min.js') }}" type="text/javascript"></script>
<script src="{{ $path_presenter::backend_assets('js/bootstrap-notify.js') }}" type="text/javascript"></script>
<script src="{{ $path_presenter::backend_assets('js/uikit.min.js') }}" type="text/javascript"></script>
<script src="{{ $path_presenter::backend_assets('js/uikit-icons.min.js') }}" type="text/javascript"></script>
<script src="{{ $path_presenter::backend_assets('js/dashboard.js?v=1.0.0') }}" type="text/javascript"></script>
@if (isset($component_datas['use_drag_rearrange']) && $component_datas['use_drag_rearrange'])
<script src="https://cdnjs.cloudflare.com/ajax/libs/TableDnD/0.9.1/jquery.tablednd.js" integrity="sha256-d3rtug+Hg1GZPB7Y/yTcRixO/wlI78+2m08tosoRn7A=" crossorigin="anonymous"></script>
@endif
<script src="{{ $path_presenter::backend_assets('js/vue.min.js?v=2.6.10') }}" type="text/javascript"></script>
<script src="{{ $path_presenter::backend_assets('js/moment.min.js') }}" type="text/javascript"></script>
<script src="{{ $path_presenter::backend_assets('js/axios.min.js') }}" type="text/javascript"></script>
@yield('js')
<script>

@if (isset($component_datas['use_drag_rearrange']) && $component_datas['use_drag_rearrange'])
$(document).ready(function() {
    $("#row-table").tableDnD({
        dragHandle: ".drag",
        onDrop: function(table, row) {
            var rows = table.tBodies[0].rows;
            var new_sort = [];
            for (var i=0; i<rows.length; i++) {
                new_sort[i] = rows[i].id;
            }
            axios.get('{{ url($uri . 'drag-sort') }}?new_sort=' + new_sort.toString())
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
    mounted: function () {
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
</html>
