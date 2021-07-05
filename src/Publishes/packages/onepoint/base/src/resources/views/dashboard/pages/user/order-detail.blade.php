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
    @if (!is_null($order_detail))
        <div class="card-update">
            <div class="row">
                <div class="col-12">
                    <div class="card-title">訂單細節</div>
                    {{-- http://backend.3dmats.test/backend/user/detail?user_id=2&realname=&cellphone_number=&email=&has_warranty=1 --}}
                    <a class="btn btn-outline-deep-purple waves-effect d-xs-block" href="{{ url($uri . 'detail?' . $base_service->getQueryString(true, true)) }}">
                        <i class="fa fa-fw fa-arrow-left"></i>回上頁
                    </a>
                </div>
                <div class="col-md-4">
                    <div class="form-body">
                        <div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <th>訂購時間</th>
                                    <td>{{ $order_detail->created_at }}</td>
                                </tr>
                                <tr>
                                    <th>訂單編號</th>
                                    <td>{{ $order_detail->order_number }}</td>
                                </tr>
                                <tr>
                                    <th>電話</th>
                                    <td>{{ $order_detail->recipient_tel }}</td>
                                </tr>
                                <tr>
                                    <th>訂購人</th>
                                    <td>{{ $order_detail->recipient_name }}</td>
                                </tr>
                                <tr>
                                    <th>寄送地址</th>
                                    <td>{{ $order_detail->county . $order_detail->districtrict . $order_detail->recipient_address }}</td>
                                </tr>
                                {{-- <tr>
                                    <th>可收貨時間</th>
                                    <td>{{ $order_detail->delivery_time }}</td>
                                </tr> --}}
                                <tr>
                                    <th>付款方式</th>
                                    <td>{{ $order_detail->payment }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-body">
                        <div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <th>折扣碼</th>
                                    <td>{{ $order_detail->coupon_code }}</td>
                                </tr>
                                <tr>
                                    <th>折扣金額</th>
                                    <td>{{ $order_detail->coupon_discount }}</td>
                                </tr>
                                <tr>
                                    <th>其他折扣</th>
                                    <td>{{ $order_detail->discount }}</td>
                                </tr>
                                <tr>
                                    <th>折扣說明</th>
                                    <td>{{ $order_detail->discount_note }}</td>
                                </tr>
                                <tr>
                                    <th>運費</th>
                                    <td>{{ $order_detail->freight }}</td>
                                </tr>
                                <tr>
                                    <th>總計</th>
                                    <td>{{ $order_detail->total }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-body">
                        <div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <th>運費</th>
                                    <td>{{ $order_detail->freight }}</td>
                                </tr>
                                <tr>
                                    <th>貨運類別</th>
                                    <td>{{ $order_detail->ship_type }}</td>
                                </tr>
                                <tr>
                                    <th>貨運編號</th>
                                    <td>{{ $order_detail->ship_no }}</td>
                                </tr>
                                <tr>
                                    <th>貨運時間</th>
                                    <td>{{ $order_detail->ship_at }}</td>
                                </tr>
                                <tr>
                                    <th>訂購者備註</th>
                                    <td>{{ $order_detail->user_remark }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-body">
                        <div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <th>發票</th>
                                    <td>{{ $order_detail->invoice_type }}</td>
                                </tr>
                                <tr>
                                    <th>公司名</th>
                                    <td>{{ $order_detail->invoice_title }}</td>
                                </tr>
                                <tr>
                                    <th>統一編號</th>
                                    <td>{{ $order_detail->invoice }}</td>
                                </tr>
                                <tr>
                                    <th>發票號碼</th>
                                    <td>{{ $order_detail->receipt_number }}</td>
                                </tr>
                                <tr>
                                    <th>開發票日期</th>
                                    <td>{{ $order_detail->receipt_number_date }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-body">
                        <div class="table-responsive">
                            <table class="table">
                                {{-- <tr>
                                    <th>card_period</th>
                                    <td>{{ $order_detail->card_period }}</td>
                                </tr>
                                <tr>
                                    <th>payment_error</th>
                                    <td>{{ $order_detail->payment_error }}</td>
                                </tr>
                                <tr>
                                    <th>virtual_atm_bankid</th>
                                    <td>{{ $order_detail->virtual_atm_bankid }}</td>
                                </tr>
                                <tr>
                                    <th>virtual_atm</th>
                                    <td>{{ $order_detail->virtual_atm }}</td>
                                </tr>
                                <tr>
                                    <th>virtual_atm_expire_date</th>
                                    <td>{{ $order_detail->virtual_atm_expire_date }}</td>
                                </tr> --}}
                                <tr>
                                    <th>訂購者IP</th>
                                    <td>{{ $order_detail->ip }}</td>
                                </tr>
                                <tr>
                                    <th>管理者備註</th>
                                    <td>{{ $order_detail->note }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card-title">訂購項目</div>
                    <table class="table">
                        <tr>
                            <th>產品編號</th>
                            <th>產品名稱</th>
                            <th>單價</th>
                            <th>數量</th>
                            <th>小計</th>
                            <th>顏色</th>
                            <th>規格一</th>
                            <th>規格二</th>
                            <th>車廠</th>
                            <th>車型</th>
                            <th>年份</th>
                        </tr>
                        @if ($order_detail->order_item->count())
                            @foreach ($order_detail->order_item as $order_item)
                                <tr>
                                    <td>{{ $order_item->complete_code }}</td>
                                    <td>{{ $order_item->name }}</td>
                                    <td>{{ $order_item->price }}</td>
                                    <td>{{ $order_item->qty }}</td>
                                    <td>{{ $order_item->sub_total }}</td>
                                    <td>{{ $order_item->color }}</td>
                                    <td>{{ $order_item->spec1 }}</td>
                                    <td>{{ $order_item->spec2 }}</td>
                                    <td>{{ $order_item->car_brand }}</td>
                                    <td>{{ $order_item->car_brand_type }}</td>
                                    <td>{{ $order_item->car_year }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </table>
                </div>
            </div>
        </div>
    @endif
@endsection