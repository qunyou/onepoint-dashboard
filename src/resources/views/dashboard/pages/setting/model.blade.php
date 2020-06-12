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
                <a href="{{ url(config('dashboard.uri') . '/setting/model') }}">
                    @lang('setting.網站設定')
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
    <div class="card-list">
        <div class="row justify-content-between px-3">
            <div class="col-5 col-md-auto">
                <div class="card-title">{{ $page_title }}</div>
            </div>
            <div class="col-7 col-md-auto">
                @if (count(config('backend.setting.model')) > 1)
                    <div class="dropdown">
                        <button class="btn btn-outline-deep-purple waves-effect dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            @lang('setting.項目類別')
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            @foreach (config('backend.setting.model') as $setting_model => $setting_title)
                                <a class="dropdown-item{{ request('model', '') == $setting_model ? ' active' : '' }}" href="{{ url($uri . 'model?model=' . $setting_model) }}">
                                    {{ $setting_title }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        @if ($list)
            <form action="" method="post">
                @csrf
                @method('PUT')
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">@lang('setting.項目')</th>
                            <th scope="col" class="d-none d-md-table-cell">@lang('setting.設定值')</th>
                            <th scope="col"></th>
                            @if (auth()->user()->hasAccess(['update-' . $permission_controller_string]))
                                <th scope="col" class="th_sort_width d-none d-md-table-cell">@lang('dashboard::backend.排序')</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($list as $element)
                            <tr>
                                <td>{{ $element->title }}</td>
                                <td class="d-none d-md-table-cell">
                                    {!! $setting_presenter->settingValueDisplay($element) !!}
                                </td>
                                <td>
                                    @if (auth()->user()->hasAccess(['update-' . $permission_controller_string]))
                                        @component('dashboard::components.dropdown-toggle', [
                                                'items' => [
                                                    '檢視' => ['url' => url($uri . 'model-detail?model=' . $model . '&setting_id=' . $element->id)],
                                                ]
                                            ])
                                            <a class="btn btn-outline-deep-purple waves-effect" href="{{ url($uri . 'model-update?model=' . $element->model . '&setting_id=' . $element->id) }}">
                                                <i class="fas fa-edit"></i> @lang('dashboard::backend.編輯')
                                            </a>
                                        @endcomponent
                                    @else
                                        <a class="btn btn-outline-deep-purple waves-effect" href="{{ url($uri . 'model-detail?model=' . $model . '&setting_id=' . $element->id) }}">
                                            <i class="fas fa-info"></i> @lang('dashboard::backend.檢視')
                                        </a>
                                    @endif
                                </td>
                                @if (auth()->user()->hasAccess(['update-' . $permission_controller_string]))
                                    <td class="d-none d-md-table-cell">
                                        <input type="text" name="sort[{{ $element->id }}]" class="form-control" value="{{ $element->sort }}" />
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <footer>
                    <div class="row">
                        <div class="col d-none d-md-inline">
                        </div>
                        <div class="col-md-auto">
                            {!! $list->appends($qs)->links() !!}
                            <div class="text-center">
                                @lang('dashboard::backend.共') {{ $list->total() }} @lang('dashboard::backend.筆資料')
                            </div>
                        </div>
                        <div class="col d-none d-md-inline">
                            @if (!$trashed)
                                @if (auth()->user()->hasAccess(['update-' . $permission_controller_string]))
                                    <div class="btn-group float-right">
                                        <button type="submit" name="set_sort" value="set_sort" class="btn btn-outline-deep-purple waves-effect"><i class="fa fa-fw fa-sort"></i>{{ trans('backend.修改排序') }}</button>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </footer>
            </form>
        @endif
@endsection
