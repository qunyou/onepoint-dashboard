<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="keywords" content="{{ config('backend.meta_keywords', __('backend.網站內容管理系統')) }}" />
    <meta name="description" content="{{ config('backend.meta_description', __('backend.網站內容管理系統')) }}">
    <meta name="author" content="Onepoint">
    @if (config('backend.favicon', false))
        <link rel="shortcut icon" href="{{ url(config('backend.favicon')) }}" type="image/x-icon">
        <link rel="icon" href="{{ url(config('backend.favicon')) }}" type="image/x-icon">
    @endif
    <link rel="stylesheet" href="{{ $path_presenter::backend_assets('css/style.min.css?v=1.0.0') }}" />
    {{-- <link rel="stylesheet" href="{{ $path_presenter::backend_assets('fontawesome/css/all.css') }}" /> --}}
    <style>
        .wrapper-full-page {
            min-height: 100vh;
            height: 100%;
        }

        .wrapper {
            position: relative;
            top: 0;
            height: 100vh;
        }

        .full-page:before {
            opacity: .33;
            background: #000000;
        }

        .full-page:before,
        .full-page:after {
            display: block;
            content: "";
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 2;
        }

        .full-page>.content:first-of-type {
            padding-top: 20vh;
        }

        .full-page>.content {
            min-height: calc(100vh - 70px);
            z-index: 4;
            position: relative;
        }

        .card.card-login {
            box-shadow: 0 25px 30px -13px rgba(40, 40, 40, 0.4);
            border-radius: 10px;
            padding-top: 10px;
            padding-bottom: 30px;
            -webkit-transform: translate3d(0, 0px, 0);
            -moz-transform: translate3d(0, 0px, 0);
            -o-transform: translate3d(0, 0px, 0);
            -ms-transform: translate3d(0, 0px, 0);
            transform: translate3d(0, 0px, 0);
            -webkit-transition: all 300ms linear;
            -moz-transition: all 300ms linear;
            -o-transition: all 300ms linear;
            -ms-transition: all 300ms linear;
            transition: all 300ms linear;
        }

        .card {
            border-radius: 4px;
            background-color: #FFFFFF;
            margin-bottom: 30px;
        }

        .card .card-header {
            padding: 15px 15px 0;
            background-color: #FFFFFF;
            border-bottom: none !important;
        }

        .card-header:first-child {
            border-radius: calc(.25rem - 1px) calc(.25rem - 1px) 0 0;
        }

        .card .card-body {
            padding: 15px 15px 10px 15px;
        }

        .card .card-footer {
            padding: 15px 15px 10px 15px;
            background-color: transparent;
            line-height: 30px;
            border-top: none !important;
            font-size: 14px;
        }

        .full-page .full-page-background {
            position: absolute;
            z-index: 1;
            height: 100%;
            width: 100%;
            display: block;
            top: 0;
            left: 0;
            background-size: cover;
            background-position: center center;
            opacity: .7;
        }
    </style>

    {{-- 網頁標題 --}}
    <title>{{ config('backend.html_page_title', __('backend.網站內容管理系統')) }}</title>
</head>

<body>
    <div class="wrapper wrapper-full-page">
        <div class="full-page section-image">
            <div class="content">
                <div class="container">
                    <div class="col-md-8 col-lg-4 ml-auto mr-auto">
                        <form action="{{ url(config('dashboard.uri') . '/login?lang=') . cache('backend_language', 'zh-tw') }}" method="post">
                            @csrf
                            <div class="card card-login p-3">
                                <div class="card-header ">
                                    <h3 class="header text-center">@lang('auth.登入')</h3>
                                </div>
                                <div class="card-body ">
                                    <div class="form-group">
                                        <label for="inputUsername">@lang('auth.帳號')</label>
                                        <input type="text" name="username" id="inputUsername" class="form-control form-control-lg" placeholder="@lang('auth.帳號')" required autofocus>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputPassword">@lang('auth.密碼')</label>
                                        <input type="password" name="password" id="inputPassword" class="form-control form-control-lg" placeholder="@lang('auth.密碼')" required>
                                    </div>
                                    <div class="form-group">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="remember" value="remember-me"> @lang('auth.讓我保持登入')
                                            </label>
                                            <p><small class="help text-danger">@lang('auth.公用電腦請勿勾選')</small></p>
                                        </div>
                                    </div>
                                    {{--
                                    <div class="form-group">
                                        <a href="#">忘記密碼？</a>
                                    </div> --}}
                                </div>
                                <div class="ml-auto mr-auto">
                                    <button class="btn btn-outline-deep-purple waves-effect btn-lg" type="submit">@lang('auth.登入')</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="full-page-background" style="background-image: url({{ $path_presenter::backend_assets('img/login-background.jpg') }}) "></div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="loginMsg" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">@lang('auth.登入訊息')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{ session('login_message', '') }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-deep-purple waves-effect btn-lg" data-dismiss="modal">@lang('backend.關閉')</button>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="{{ $path_presenter::backend_assets('js/jquery-3.3.1.min.js') }}" type="text/javascript"></script>
<script src="{{ $path_presenter::backend_assets('js/popper.min.js') }}" type="text/javascript"></script>
<script src="{{ $path_presenter::backend_assets('js/bootstrap.min.js') }}" type="text/javascript"></script>

@if (session()->has('login_message'))
<script>
    $(function() {
        $('#loginMsg').modal('show');
    });
</script>
@endif

</html>
