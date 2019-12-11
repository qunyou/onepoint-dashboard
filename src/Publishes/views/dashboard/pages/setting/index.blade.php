@inject('setting_presenter', 'Onepoint\Dashboard\Presenters\SettingPresenter')

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
                <a href="{{ url(config('dashboard.uri') . '/setting/index') }}">
                    @lang('setting.網站設定')
                </a>
            </li>

            @if ($version)
                <li class="breadcrumb-item" aria-current="page">
                    <a href="#">
                        @lang('backend.版本檢視')
                    </a>
                </li>
            @endif
        </ol>
    </nav>
@endsection

@section('main_block')
    <form action="" method="post">
        @csrf
        @method('PUT')
        <div class="card card-list">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <div class="card-title">{{ $page_title }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="float-right">
                            <div class="btn-group">
                                @if (!$trashed)
                                    <div class="dropdown">
                                        <button class="btn btn-outline-deep-purple waves-effect dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            @lang('setting.項目類別')
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            @foreach (config('backend.setting.model') as $setting_model => $setting_title)
                                                <a class="dropdown-item{{ request('model', '') == $setting_model ? ' active' : '' }}" href="{{ url($uri . 'index?model=' . $setting_model) }}">{{ $setting_title }}</a>
                                            @endforeach
                                        </div>
                                    </div>
                                    <a class="btn btn-outline-deep-purple waves-effect" href="{{ url($uri . 'index?trashed=true') }}">
                                        <i class="fa fa-fw fa-recycle"></i><span class="d-none d-md-inline">@lang('backend.資源回收')</span>
                                    </a>
                                    <a class="btn btn-outline-deep-purple waves-effect" href="{{ url($uri . 'update') }}">
                                        <i class="fa fa-fw fa-plus"></i><span class="d-none d-md-inline">@lang('backend.新增')</span>
                                    </a>
                                @else
                                    <a class="btn btn-outline-deep-purple waves-effect" href="{{ url($uri . 'index') }}"><i class="fa fa-fw fa-arrow-left"></i><span class="d-none d-md-inline">@lang('backend.回列表')</span></a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if ($list)
                <div class="card-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th class="check_all_width d-none d-md-table-cell">
                                    <input type="checkbox" name="select_all" id="select_all" value="" data-toggle="tooltip" data-original-title="@lang('backend.全選')" />
                                </th>
                                <th scope="col">@lang('backend.標題')</th>
                                <th scope="col" class="d-none d-md-table-cell">@lang('backend.說明')</th>
                                <th scope="col" class="d-none d-md-table-cell">model</th>
                                <th scope="col" class="d-none d-md-table-cell">設定索引</th>
                                <th scope="col" class="d-none d-md-table-cell">設定值</th>
                                @if (!$trashed)
                                    <th scope="col"></th>
                                    <th scope="col" class="th_sort_width d-none d-md-table-cell">@lang('backend.排序')</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($list as $element)
                                <tr{!! $element->status == '停用' ? ' class="table-dark"' : '' !!}>
                                    <td class="d-none d-md-table-cell">
                                        <input type="checkbox" name="checked_id[]" class="checkbox" value="{{ $element->id }}" />
                                    </td>
                                    <td>{{ $element->title }}</td>
                                    <td class="d-none d-md-table-cell">{{ $element->description }}</td>
                                    <td class="d-none d-md-table-cell">{{ $element->model }}</td>
                                    <td class="d-none d-md-table-cell">{{ $element->setting_key }}</td>
                                    <td class="d-none d-md-table-cell">
                                        {!! $setting_presenter->settingValueDisplay($element) !!}
                                    </td>
                                    @if (!$trashed)
                                        <td>
                                            @component('dashboard::components.dropdown-toggle', [
                                                    'items' => [
                                                        '檢視' => ['url' => url($uri . 'detail?setting_id=' . $element->id)],
                                                    ]
                                                ])
                                                <a class="btn btn-outline-deep-purple waves-effect" href="{{ url($uri . 'update?setting_id=' . $element->id) }}">
                                                    <i class="fas fa-edit fa-fw"></i> @lang('backend.編輯')
                                                </a>
                                            @endcomponent
                                        </td>
                                        <td class="d-none d-md-table-cell">
                                            <input type="text" name="sort[{{ $element->id }}]" class="form-control" value="{{ $element->sort }}" />
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <footer class="card-footer">
                    <div class="row">
                        <div class="col d-none d-md-inline">
                            <div class="btn-group">
                                <div class="dropdown">
                                    <button class="btn btn-outline-deep-purple waves-effect dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        @lang('backend.選取項目')
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        @if ($trashed)
                                            <button type="submit" name="force_delete" value="force_delete" class="dropdown-item">
                                                <i class="fa fa-trash"></i>@lang('backend.永久刪除')
                                            </button>
                                            <button type="submit" name="restore" value="restore" class="dropdown-item">
                                                <i class="fa fa-recycle"></i>@lang('backend.還原')
                                            </button>
                                        @else
                                            {{-- <button type="submit" name="status_enable" value="status_enable" class="dropdown-item">
                                                <i class="fas fa-eye"></i>@lang('backend.啟用')
                                            </button>
                                            <button type="submit" name="status_disable" value="status_disable" class="dropdown-item">
                                                <i class="fas fa-eye-slash"></i>@lang('backend.停用')
                                            </button> --}}
                                            @if (!isset($footer_delete_hide) || (isset($footer_delete_hide) && !$footer_delete_hide))
                                                <button type="submit" name="delete" value="delete" class="dropdown-item">
                                                    <i class="fa fa-trash"></i>@lang('backend.刪除')
                                                </button>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-auto">
                            {!! $list->appends($qs)->links() !!}
                            <div class="text-center">
                                @lang('backend.共') {{ $list->total() }} @lang('backend.筆資料')
                            </div>
                        </div>
                        <div class="col d-none d-md-inline">
                            @if (!$trashed)
                                <div class="btn-group float-right">
                                    <button type="submit" name="set_sort" value="set_sort" class="btn btn-outline-deep-purple waves-effect"><i class="fa fa-fw fa-sort"></i>@lang('backend.修改排序')</button>
                                </div>
                            @endif
                        </div>
                    </div>
                </footer>
            @endif
        </div>
    </form>
@endsection
