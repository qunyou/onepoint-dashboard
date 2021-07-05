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
    @if (!is_null($warranty_detail))
        <div class="card-update">
            <div class="row">
                <div class="col-12">
                    <div class="card-title">保固登錄細節</div>
                    <a class="btn btn-outline-deep-purple waves-effect d-xs-block" href="{{ url($uri . 'index?' . $base_service->getQueryString(true, true, ['user_id'])) }}">
                        <i class="fa fa-fw fa-arrow-left"></i>回上頁
                    </a>
                </div>
                <div class="col-md-4">
                    <div class="form-body">
                        <div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <th>登錄時間</th>
                                    <td>{{ $warranty_detail->created_at }}</td>
                                </tr>
                                <tr>
                                    <th>狀態</th>
                                    <td>{{ $warranty_detail->status }}</td>
                                </tr>
                                <tr>
                                    <th>審核</th>
                                    <td>{{ $warranty_detail->verify }}</td>
                                </tr>
                                <tr>
                                    <th>姓名</th>
                                    <td>{{ $warranty_detail->realname }}</td>
                                </tr>
                                <tr>
                                    <th>性別</th>
                                    <td>{{ $warranty_detail->gender }}</td>
                                </tr>
                                <tr>
                                    <th>年齡</th>
                                    <td>{{ $warranty_detail->age }}</td>
                                </tr>
                                <tr>
                                    <th>手機</th>
                                    <td>{{ $warranty_detail->cellphone }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $warranty_detail->email }}</td>
                                </tr>
                                <tr>
                                    <th>password</th>
                                    <td>{{ $warranty_detail->password }}</td>
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
                                    <th>purchase_at</th>
                                    <td>{{ $warranty_detail->purchase_at }}</td>
                                </tr>
                                <tr>
                                    <th>qrcode_number</th>
                                    <td>{{ $warranty_detail->qrcode_number }}</td>
                                </tr>
                                <tr>
                                    <th>barcode_number</th>
                                    <td>{{ $warranty_detail->barcode_number }}</td>
                                </tr>
                                <tr>
                                    <th>product_type</th>
                                    <td>{{ $warranty_detail->product_type }}</td>
                                </tr>
                                <tr>
                                    <th>product_category</th>
                                    <td>{{ $warranty_detail->product_category }}</td>
                                </tr>
                                <tr>
                                    <th>purchase_channel_type</th>
                                    <td>{{ $warranty_detail->purchase_channel_type }}</td>
                                </tr>
                                <tr>
                                    <th>purchase_channel</th>
                                    <td>{{ $warranty_detail->purchase_channel }}</td>
                                </tr>
                                <tr>
                                    <th>gotshop</th>
                                    <td>{{ $warranty_detail->gotshop }}</td>
                                </tr>
                                <tr>
                                    <th>picture</th>
                                    <td>{{ $warranty_detail->picture }}</td>
                                </tr>
                                <tr>
                                    <th>how_know</th>
                                    <td>{{ $warranty_detail->how_know }}</td>
                                </tr>
                                <tr>
                                    <th>reason</th>
                                    <td>{{ $warranty_detail->reason }}</td>
                                </tr>
                                <tr>
                                    <th>coupon_code</th>
                                    <td>{{ $warranty_detail->coupon_code }}</td>
                                </tr>
                                <tr>
                                    <th>disable</th>
                                    <td>{{ $warranty_detail->disable }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection