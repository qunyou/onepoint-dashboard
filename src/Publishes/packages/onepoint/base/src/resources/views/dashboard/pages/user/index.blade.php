@extends('dashboard::layouts.dashboard')

@section('sidebar-header')
    @include('base::dashboard.includes.sidebar-header')
@endsection

@section('page-header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item" aria-current="page">
                <a href="{{ url(config('dashboard.uri') . '/dashboard/index') }}">
                    Home
                </a>
            </li>
            <li class="breadcrumb-item" aria-current="page">
                <a href="{{ url(config('dashboard.uri') . '/user/index') }}">
                    @lang('base::user.會員')
                </a>
            </li>

            @if ($version)
                <li class="breadcrumb-item" aria-current="page">
                    <a href="#">
                        @lang('dashboard::backend.版本檢視')
                    </a>
                </li>
            @endif
        </ol>
    </nav>
@endsection

@section('main_block')
    @component('dashboard::components.backend-list', $component_datas)
        @slot('button_block')
            <a class="btn btn-outline-deep-purple waves-effect" data-toggle="collapse" href="#collapseGuide" role="button" aria-expanded="false" aria-controls="collapseGuide">
                <i class="fa fa-fw fa-search"></i><span class="d-none d-md-inline">查詢</span>
            </a>
        @endslot
        @slot('search_block')
            <div class="card-update collapse search show" id="collapseGuide">
                <div class="form-body p-3">
                    <form action="" method="get">
                        <div class="row">
                            <div class="col-auto">
                                姓名
                                <input type="text" name="realname" class="form-control" value="{{ request('realname', '') }}" placeholder="會員姓名">
                            </div>
                            <div class="col-auto">
                                手機號碼
                                <input type="text" name="cellphone_number" class="form-control" value="{{ request('cellphone_number', '') }}" placeholder="手機號碼">
                            </div>
                            <div class="col-auto">
                                Email
                                <input type="text" name="email" class="form-control" value="{{ request('email', '') }}" placeholder="Email">
                            </div>
                            <div class="col-auto pt-4">
                                <input type="checkbox" class="form-check-input" value="1" name="has_warranty" id="has_warranty" {{ request('has_warranty', 0) ? 'checked' : ''}}>
                                <label class="form-check-label" for="has_warranty">有保固資料</label>
                            </div>
                            {{-- <div class="col-auto pt-4">
                                <input type="checkbox" class="form-check-input" value="1" name="has_order" id="has_order" {{ request('has_order', 0) ? 'checked' : ''}}>
                                <label class="form-check-label" for="has_order">有訂單資料</label>
                            </div> --}}
                        </div>

                        <div class="text-center mt-2">
                            <div class="btn-group">
                                <a class="btn btn-outline-deep-purple waves-effect" data-toggle="collapse" href="#collapseGuide" role="button" aria-expanded="false" aria-controls="collapseGuide">
                                    關閉
                                </a>
                                <a class="btn btn-outline-deep-purple waves-effect" href="{{ url($uri . 'index') }}">
                                    重設查詢
                                </a>
                                <button type="submit" class="btn btn-outline-deep-purple waves-effect">
                                    查詢
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endslot
    @endcomponent
@endsection