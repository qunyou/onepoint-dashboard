@extends('dashboard::layouts.dashboard')

@section('top-item')
    @include('base::dashboard.includes.top-item')
    @parent
@endsection

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
        </ol>
    </nav>
@endsection

@section('main_block')
    <div class="card-update">
        <div class="row">
            @if (config('backend.analytics.enable', false))
                <div class="col-lg-3 mb-5">
                    <h4><span class="bd-content-title">訂單累計總金額</span></h4>
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            昨日訂單累計總金額
                            <span class="badge badge-primary badge-pill">{{ $order_form_total_yesterday }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            本月訂單累計總金額
                            <span class="badge badge-primary badge-pill">{{ $order_form_total_month }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            上月訂單累計總金額
                            <span class="badge badge-primary badge-pill">{{ $order_form_total_prev_month }}</span>
                        </li>
                    </ul>
                </div>
    
                @if ($member_order_total_top5->count())
                    <div class="col-lg-3 mb-5">
                        <h4><span class="bd-content-title">會員消費金額 TOP5</span></h4>
                        <ul class="list-group">
                            @foreach ($member_order_total_top5 as $item)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $item->realname }}
                                    <span class="badge badge-primary badge-pill">{{ $item->order_form_total }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if ($order_item_month_top5->count())
                    <div class="col-lg-3 mb-5">
                        <h4><span class="bd-content-title">本月熱門商品銷售數量 TOP5</span></h4>
                        <ul class="list-group">
                            @foreach ($order_item_month_top5 as $item)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $item->product->product_name }}
                                    <span class="badge badge-primary badge-pill">{{ $item->total }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if ($order_item_last_month_top5->count())
                    <div class="col-lg-3 mb-5">
                        <h4><span class="bd-content-title">上個月熱門商品銷售數量 TOP5</span></h4>
                        <ul class="list-group">
                            @foreach ($order_item_last_month_top5 as $item)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $item->product->product_name }}
                                    <span class="badge badge-primary badge-pill">{{ $item->total }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if ($order_item_month_price_top5->count())
                    <div class="col-lg-3 mb-5">
                        <h4><span class="bd-content-title">本月熱門商品銷售金額 TOP5</span></h4>
                        <ul class="list-group">
                            @foreach ($order_item_month_price_top5 as $item)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $item->product->product_name }}
                                    <span class="badge badge-primary badge-pill">{{ $item->sum }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if ($order_item_last_month_price_top5->count())
                    <div class="col-lg-3 mb-5">
                        <h4><span class="bd-content-title">本月熱門商品銷售金額 TOP5</span></h4>
                        <ul class="list-group">
                            @foreach ($order_item_last_month_price_top5 as $item)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $item->product->product_name }}
                                    <span class="badge badge-primary badge-pill">{{ $item->sum }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="col-lg-6 mb-5">
                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">一週總體流量統計</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">一週各頁最高造訪數(TOP10)</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false">一週流量來源媒介統計</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="pills-user-types-tab" data-toggle="pill" href="#pills-user-types" role="tab" aria-controls="pills-user-types" aria-selected="false">一週流量管道統計</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                            <table class="table">
                                <tr>
                                    <th>日期</th>
                                    <th>使用者</th>
                                    <th>瀏覽量</th>
                                </tr>
                                @foreach ($analytics_total_visitor as $item)
                                <tr>
                                    <td>{{ $item['date']->format('Y-m-d') }}</td>
                                    <td>{{ $item['visitors'] }}</td>
                                    <td>{{ $item['pageViews'] }}</td>
                                </tr>
                                @endforeach
                            </table>
                        </div>
                        <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                            <table class="table">
                                <tr>
                                    <th>網址</th>
                                    {{-- <th>pageTitle</th> --}}
                                    <th>瀏覽量</th>
                                </tr>
                                @foreach ($analytics_most_visited_pages as $item)
                                <tr>
                                    <td>{{ $item['url'] }}</td>
                                    {{-- <td>{{ $item['pageTitle'] }}</td> --}}
                                    <td>{{ $item['pageViews'] }}</td>
                                </tr>
                                @endforeach
                            </table>
                        </div>
                        <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
                            <table class="table">
                                <tr>
                                    <th>來源</th>
                                    <th>使用者</th>
                                </tr>
                                @foreach ($analytics_top_referrers as $item)
                                <tr>
                                    <td>{{ $item['url'] }}</td>
                                    <td>{{ $item['pageViews'] }}</td>
                                </tr>
                                @endforeach
                            </table>
                        </div>
            
                        <div class="tab-pane fade" id="pills-user-types" role="tabpanel" aria-labelledby="pills-user-types-tab">
                            <table class="table">
                                <tr>
                                    <th>管道</th>
                                    <th>使用者</th>
                                </tr>
                                @foreach ($analytics_user_types as $item)
                                <tr>
                                    <td>{{ $item['type'] }}</td>
                                    <td>{{ $item['sessions'] }}</td>
                                </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                    {{--
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <h4>最近6個月 page views</h4>
                            <table class="table">
                                <tr>
                                    <th>date</th>
                                    <th>pageTitle</th>
                                    <th>visitors</th>
                                    <th>pageViews</th>
                                </tr>
                                @foreach ($analytics_page_view_six_months as $item)
                                <tr>
                                    <td>{{ $item['date']->format('Y-m-d') }}</td>
                                    <td>{{ $item['pageTitle'] }}</td>
                                    <td>{{ $item['visitors'] }}</td>
                                    <td>{{ $item['pageViews'] }}</td>
                                </tr>
                                @endforeach
                            </table>
                        </div> 
                        <table class="table">
                            <tr>
                                <th>date</th>
                                <th>pageTitle</th>
                                <th>使用者</th>
                                <th>pageViews</th>
                            </tr>
                            @foreach ($analytics_visitor_pageviews as $item)
                            <tr>
                                <td>{{ $item['date']->format('Y-m-d') }}</td>
                                <td>{{ $item['pageTitle'] }}</td>
                                <td>{{ $item['visitors'] }}</td>
                                <td>{{ $item['pageViews'] }}</td>
                            </tr>
                            @endforeach
                        </table>
                        <div class="col-md-4 mb-3">
                            <h4>一週瀏覽器造訪數</h4>
                            <table class="table">
                                <tr>
                                    <th>browser</th>
                                    <th>sessions</th>
                                </tr>
                                @foreach ($analytics_top_browsers as $item)
                                <tr>
                                    <td>{{ $item['browser'] }}</td>
                                    <td>{{ $item['sessions'] }}</td>
                                </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                    --}}
                </div>
            @endif
        </div>
    </div>

    @if (isset($show_index_count) && $show_index_count)
        <div class="card-update">
            <div class="row">
                @isset($all_visitor)
                    <div class="col-lg-2">
                        <div class="form-body text-center pt-4">
                            <i class="fas fa-chart-line fa-3x"></i>
                            <p class="mt-3">總造訪人次</p>
                            <h1>{{ $all_pages }}</h1>
                        </div>
                    </div>
                @endisset

                @isset($all_pages)
                    <div class="col-lg-2">
                        <div class="form-body text-center pt-4">
                            <i class="fas fa-chart-line fa-3x"></i>
                            <p class="mt-3">總瀏覽數</p>
                            <h1>{{ $all_pages }}</h1>
                        </div>
                    </div>
                @endisset

                @isset($this_week_visitor)
                    <div class="col-lg-2">
                        <div class="form-body text-center pt-4">
                            <i class="fas fa-calendar-week fa-3x"></i>
                            <p class="mt-3">本週造訪人次</p>
                            <h1>{{ $this_week_visitor }}</h1>
                        </div>
                    </div>
                @endisset

                @isset($this_week_pages)
                    <div class="col-lg-2">
                        <div class="form-body text-center pt-4">
                            <i class="fas fa-calendar-week fa-3x"></i>
                            <p class="mt-3">本週瀏覽數</p>
                            <h1>{{ $this_week_pages }}</h1>
                        </div>
                    </div>
                @endisset
                
                @isset($blog_count)
                    <div class="col-lg-2">
                        <div class="form-body text-center pt-4">
                            <a href="{{ url(config('dashboard.uri') . '/blog/index') }}">
                                <i class="fas fa-blog fa-3x"></i>
                                <p class="mt-3">部落格文章數量</p>
                            </a>
                            <h1>{{ $blog_count }}</h1>
                        </div>
                    </div>
                @endisset

                @isset($article_count)
                    <div class="col-lg-2">
                        <div class="form-body text-center pt-4">
                            <a href="{{ url(config('dashboard.uri') . '/article/index') }}">
                                <i class="far fa-file-alt fa-3x"></i>
                                <p class="mt-3">文章數量</p>
                            </a>
                            <h1>{{ $article_count }}</h1>
                        </div>
                    </div>
                @endisset

                {{-- <div class="col-lg-2">
                    <div class="form-body text-center pt-4">
                        <a href="{{ url(config('dashboard.uri') . '/interior-design/index') }}">
                            <i class="fas fa-couch fa-3x"></i>
                            <p class="mt-3">設計案數量</p>
                        </a>
                        <h1>{{ $interior_design_count ?? 0 }}</h1>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-body text-center pt-4">
                        <a href="{{ url(config('dashboard.uri') . '/dashboard/browser-agent') }}">
                            <i class="fas fa-chart-bar fa-3x"></i>
                            <p class="mt-3">總瀏覽數</p>
                        </a>
                        <h1>{{ $browser_agent_count ?? 0 }}</h1>
                    </div>
                </div> --}}
            </div>
        </div>
    @endif
@endsection
