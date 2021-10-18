
<!doctype html>
<html lang="{{ $backend_language }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="keywords" content="{{ config('backend.meta_keywords', __('dashboard::backend.網站內容管理系統')) }}" />
    <meta name="description" content="{{ config('backend.meta_description', __('dashboard::backend.網站內容管理系統')) }}">
    <meta name="author" content="Onepoint">
    @if (config('backend.favicon', false))
        <link rel="shortcut icon" href="{{ url(config('backend.favicon')) }}" type="image/x-icon">
        <link rel="icon" href="{{ url(config('backend.favicon')) }}" type="image/x-icon">
    @endif
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
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
    <title>{{ config('backend.html_page_title', __('dashboard::backend.網站內容管理系統')) }}</title>
</head>

<body>
    <div class="wrapper wrapper-full-page">
        <div class="full-page section-image">
            <div class="content">
                <div class="container">
                    <div class="row">
                        <div class="col-md-8 col-lg-4 mx-auto">
                            <form action="{{ url(config('dashboard.uri') . '/login?lang=' . $backend_language) }}" method="post">
                                @csrf
                                <div class="card card-login p-3">
                                    <div class="card-header ">
                                        <div class="header h-4 text-center">{{ config('app.name') }}</div>
                                    </div>
                                    <div class="card-body ">
                                        <div class="form-group mb-4">
                                            <label for="inputUsername">@lang('dashboard::auth.帳號')</label>
                                            <input type="text" name="username" id="inputUsername" class="form-control mt-1" placeholder="@lang('dashboard::auth.帳號')" required autofocus>
                                        </div>
                                        <div class="form-group mb-4">
                                            <label for="inputPassword">@lang('dashboard::auth.密碼')</label>
                                            <input type="password" name="password" id="inputPassword" class="form-control mt-1" placeholder="@lang('dashboard::auth.密碼')" required>
                                        </div>
                                        <div class="form-group">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="remember" value="remember-me"> @lang('dashboard::auth.讓我保持登入')
                                                </label>
                                                <p><small class="help text-danger">@lang('dashboard::auth.公用電腦請勿勾選')</small></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-grid gap-2">
                                        <button class="btn btn-primary" type="submit">@lang('dashboard::auth.登入')</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="full-page-background" style="background-image: url('assets/dashboard/img/login-background.jpg');"></div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="loginMsg" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">@lang('dashboard::auth.登入訊息')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{ session('login_message', '') }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">@lang('dashboard::backend.關閉')</button>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

@if (session()->has('login_message'))
<script>
    var loginMsg = new bootstrap.Modal(document.getElementById('loginMsg'), {
        keyboard: false
    })
    loginMsg.show()
</script>
@endif

</html>