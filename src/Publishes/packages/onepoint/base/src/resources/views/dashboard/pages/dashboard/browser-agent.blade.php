@extends('dashboard::layouts.dashboard')

@section('title', config('site.name'))

@section('page-header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item" aria-current="page">
                <a href="{{ url(config('dashboard.uri') . '/dashboard/index') }}">
                    Home
                </a>
            </li>
            <li class="breadcrumb-item" aria-current="page">
                <a href="{{ url(config('dashboard.uri') . '/dashboard/browser-agent') }}">
                    @lang('dashboard::backend.流量統計')
                </a>
            </li>
        </ol>
    </nav>
@endsection

@section('main_block')
    <div class="card-update">
        <div class="row">
            <div class="col-12 mt-3">
                <div class="card-title">
                    流量統計
                </div>
            </div>
            <div class="col-12">
                <div class="form-body">
                    @if ($browser_agent)
                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <th>IP</th>
                                {{-- <th>member_id</th> --}}
                                <th>縣市</th>
                                {{-- <th>州/市</th> --}}
                                <th>國家</th>
                                {{-- <th>地址</th> --}}
                                {{-- <th>國碼</th> --}}
                                <th>洲</th>
                                {{-- <th>洲碼</th> --}}
                                <th>作業系統</th>
                                <th>瀏覽器</th>
                                <th>瀏覽器版本</th>
                                <th>語言</th>
                                <th>設備</th>
                                <th>搜尋引擎</th>
                                <th>造訪網址</th>
                                <th>來源網址</th>
                            </tr>
                            @foreach ($browser_agent as $item)
                            <tr>
                                <td>{{  $item->ip }}</td>
                                {{-- <td>{{  $item->member_id }}</td> --}}
                                <td>{{  $item->city }}</td>
                                {{-- <td>{{  $item->state }}</td> --}}
                                <td>{{  $item->country }}</td>
                                {{-- <td>{{  $item->address }}</td> --}}
                                {{-- <td>{{  $item->country_code }}</td> --}}
                                <td>{{  $item->continent }}</td>
                                {{-- <td>{{  $item->continent_code }}</td> --}}
                                <td>{{  $item->platform }}</td>
                                <td>{{  $item->browser }}</td>
                                <td>{{  $item->browser_version }}</td>
                                <td>{{  $item->languages }}</td>
                                <td>{{  $item->device }}</td>
                                <td>{{  $item->robot }}</td>
                                <td>{{  $item->url_full }}</td>
                                <td>{{  $item->url_previous }}</td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
