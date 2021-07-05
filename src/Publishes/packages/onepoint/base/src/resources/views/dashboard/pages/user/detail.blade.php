@extends('dashboard::layouts.dashboard')

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
            <li class="breadcrumb-item" aria-current="page">
                <a href="#">
                    @lang('dashboard::backend.檢視')
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
    @component('dashboard::components.backend-detail-card', $component_datas)
        @slot('top_btn')
            <a class="btn btn-outline-deep-purple waves-effect d-xs-block" href="{{ url($uri . 'index?' . $base_service->getQueryString(true, true, ['user_id'])) }}">
                <i class="fa fa-fw fa-arrow-left"></i>回上頁
            </a>
        @endslot
        @include('dashboard::backend-update-input', ['form_array' => $form_array, 'form_value' => $user ?? ''])
    @endcomponent

    @if ($user->order->count())
        <div class="card-update">
            <div class="form-body">
                <div class="card-title">訂單</div>
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th>訂購時間</th>
                            <th>訂單編號</th>
                            <th>訂購人</th>
                            <th>電話</th>
                            <th>寄送地址</th>
                            <th></th>
                        </tr>
                        @foreach ($user->order as $item)
                            <tr>
                                <td>{{ $item->created_at }}</td>
                                <td>{{ $item->order_number }}</td>
                                <td>{{ $item->recipient_name }}</td>
                                <td>{{ $item->recipient_tel }}</td>
                                <td>{{ $item->county . $item->districtrict . $item->recipient_address }}</td>
                                <td class="text-right"><a href="{{ url($uri . 'order-detail?order_id=' . $item->id . '&' . $base_service->getQueryString(true, true)) }}" class="btn btn-outline-deep-purple waves-effect">@lang('dashboard::backend.檢視')</a></td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    @endif

    @if ($user->warranty->count())
        <div class="card-update">
            <div class="form-body">
                <div class="card-title">保固登錄</div>
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th>填表時間</th>
                            <th>審核</th>
                            <th>姓名</th>
                            <th>電話</th>
                            <th>QRcode</th>
                            <th>Barcode</th>
                            <th></th>
                        </tr>
                        @foreach ($user->warranty as $item)
                            <tr>
                                <td>{{ $item->created_at }}</td>
                                <td>{{ $item->verify }}</td>
                                <td>{{ $item->realname }}</td>
                                <td>{{ $item->cellphone }}</td>
                                <td>{{ $item->qrcode_number }}</td>
                                <td>{{ $item->barcode_number }}</td>
                                <td class="text-right"><a target="_blank" href="{{ url(config('dashboard.uri') . '/warranty/update?warranty_id=' . $item->id . '&' . $base_service->getQueryString(true, true)) }}" class="btn btn-outline-deep-purple waves-effect">@lang('dashboard::backend.檢視')</a></td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    @endif
@endsection