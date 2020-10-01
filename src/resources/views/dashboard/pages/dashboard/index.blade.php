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
        </ol>
    </nav>
@endsection

@section('main_block')
    
    @if (isset($analytics_visitor_pageviews))
    <div class="card-update">
        <div class="row">
            <div class="col-md-4 mb-3">
                <h4>一週造訪人數及頁面造訪數</h4>
                <table class="table">
                    <tr>
                        <th>date</th>
                        <th>visitors</th>
                        <th>pageViews</th>
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

            <div class="col-md-4 mb-3">
                <h4>一週各頁造訪次數</h4>
                <table class="table">
                    <tr>
                        <th>date</th>
                        <th>pageTitle</th>
                        <th>visitors</th>
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
            </div>

            {{-- <div class="col-md-4 mb-3">
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
            </div> --}}

            <div class="col-md-4 mb-3">
                <h4>一週各頁最高造訪數</h4>
                <table class="table">
                    <tr>
                        <th>url</th>
                        <th>pageTitle</th>
                        <th>pageViews</th>
                    </tr>
                    @foreach ($analytics_most_visited_pages as $item)
                    <tr>
                        <td>{{ $item['url'] }}</td>
                        <td>{{ $item['pageTitle'] }}</td>
                        <td>{{ $item['pageViews'] }}</td>
                    </tr>
                    @endforeach
                </table>
            </div>

            <div class="col-md-4 mb-3">
                <h4>一週來源網址統計</h4>
                <table class="table">
                    <tr>
                        <th>url</th>
                        <th>pageViews</th>
                    </tr>
                    @foreach ($analytics_top_referrers as $item)
                    <tr>
                        <td>{{ $item['url'] }}</td>
                        <td>{{ $item['pageViews'] }}</td>
                    </tr>
                    @endforeach
                </table>
            </div>

            <div class="col-md-4 mb-3">
                <h3>一週造訪者類型</h3>
                <table class="table">
                    <tr>
                        <th>type</th>
                        <th>sessions</th>
                    </tr>
                    @foreach ($analytics_user_types as $item)
                    <tr>
                        <td>{{ $item['type'] }}</td>
                        <td>{{ $item['sessions'] }}</td>
                    </tr>
                    @endforeach
                </table>
            </div>

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
    </div>
    @endif
    
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
