@inject('image_service', 'Onepoint\Dashboard\Services\ImageService')
@inject('str', 'Illuminate\Support\Str')

<div class="card-list">
    <div class="row justify-content-between px-3">
        <div class="col-12">
            <div class="card-title">
                {{ $page_title }}
                @if ($version)
                    - @lang('backend.版本檢視')
                @endif
            </div>
        </div>
        <div class="col-md-12 top-btn-group">
            <div class="btn-group d-block d-md-inline-block">
                @if ($list)
                <button class="btn btn-outline-deep-purple waves-effect dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{ $list->firstItem() }} - {{ $list->count() }}，@lang('pagination.共') {{ $list->total() }} @lang('pagination.筆資料')
                </button>
                @endif
                <div class="dropdown-menu dropdown-menu-right">
                    <a href="{{ url($uri . 'index?records_per_page=50') }}" class="dropdown-item">@lang('pagination.每頁顯示') 50 @lang('pagination.筆資料')</a>
                    <a href="{{ url($uri . 'index?records_per_page=100') }}" class="dropdown-item">@lang('pagination.每頁顯示') 100 @lang('pagination.筆資料')</a>
                    <a href="{{ url($uri . 'index?records_per_page=1000') }}" class="dropdown-item">@lang('pagination.每頁顯示') 1000 @lang('pagination.筆資料')</a>
                </div>
            </div>
            @if (!$trashed && !$version)
                @if (isset($add_url) || isset($dropdown_items) || isset($button_block))
                    @component('dashboard::components.top-btn-group', $dropdown_items)
                        {!! $button_block ?? '' !!}
                        @if (isset($add_url))
                            <a class="btn btn-outline-deep-purple waves-effect d-xs-block" href="{{ $add_url }}">
                                <i class="fa fa-plus"></i>@lang('backend.新增')
                            </a>
                        @endif
                    @endcomponent
                @endif
            @else
                <a class="btn btn-outline-deep-purple waves-effect float-right d-none d-md-inline" href="{{ $back_url }}">
                    <i class="fa fa-arrow-left"></i>@lang('backend.回列表')
                </a>
            @endif
        </div>
    </div>

    {{ $search_block ?? '' }}
    @if ($list)
    <form action="" method="post">
        @csrf
        @method('PUT')
        <div class="table-responsive">
            <table id="row-table" class="table table-hover">
                <thead>
                    <tr>
                        @if (auth()->user()->hasAccess(['update-' . $permission_controller_string]))
                            @if (!$version)
                                <th class="check_all_width d-none d-md-table-cell">
                                    <input type="checkbox" name="select_all" id="select_all" value="" data-toggle="tooltip" data-original-title="@lang('backend.全選')" />
                                </th>
                            @endif
                        @endif
                        @foreach ($th as $element)
                            <th scope="col" class="{{ $element['class'] ?? '' }} text-nowrap">{{ $element['title'] }}</th>
                        @endforeach
                        @if (!$trashed)
                            @if (auth()->user()->hasAccess(['update-' . $permission_controller_string]))
                                @if ($version)
                                    <th class="d-none d-md-table-cell">@lang('backend.版本時間')</th>
                                @endif
                            @endif
                            <th scope="col"></th>
                            @if (($use_sort ?? true) && auth()->user()->hasAccess(['update-' . $permission_controller_string]))
                                @if (!$version)
                                    @if ($use_rearrange ?? true)
                                        <th scope="col" class="th_sort_btn_width d-none d-md-table-cell"></th>
                                    @endif
                                    <th scope="col" class="th_sort_width d-none d-md-table-cell">@lang('backend.排序')</th>
                                @endif
                            @endif
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($list as $list_key => $element)
                        <tr id="{{ $element->id }}" {!! $element->status == '停用' ? ' class="table-dark"' : '' !!}>
                            @if (auth()->user()->hasAccess(['update-' . $permission_controller_string]))
                                @if (!$version)
                                    <td class="{{ $use_drag_rearrange ?? true ? 'drag' : '' }} d-none d-md-table-cell">
                                        <input type="checkbox" name="checked_id[]" class="checkbox" value="{{ $element->id }}" />
                                    </td>
                                @endif
                            @endif
                            @foreach ($column as $key => $value)
                                <td class="{{ $value['class'] ?? '' }}">
                                    @switch($value['type'])
                                        @case('belongsToMany')
                                            @if (is_array($value['column_name']))
                                                @foreach ($value['column_name'] as $item)
                                                    <div>{!! $element->{$value['with']}->implode($item, $value['delimiter_string'] ?? ',') !!}</div>
                                                @endforeach
                                            @else
                                                {!! $element->{$value['with']}->implode($value['column_name'], $value['delimiter_string'] ?? ',') !!}
                                            @endif
                                            @break
                                        @case('belongsTo')
                                            {{ $element->{$value['with']}->{$value['column_name']} ?? '' }}
                                            @break
                                        @case('badges')
                                            {{ $element->{$value['column_name']} }}<br>
                                            @foreach ($value['set_value'] as $badge_key => $badge_value)
                                                <span class="{{ $badge_value['class'] }}">
                                                    @if (isset($badge_value['belongsTo']))
                                                        @if (!is_null($element->{$badge_key}))
                                                            {{ $badge_value['badge_title'] }}{{ $element->{$badge_key}->{$badge_value['belongsTo']} }}
                                                        @endif
                                                    @else
                                                        {{ $badge_value['badge_title'] }}{{ $element->{$badge_key} }}
                                                    @endif
                                                </span>
                                            @endforeach
                                            @break
                                        @case('image')
                                            {!! $image_service->{$value['method']}($element->{$value['column_name']}, '', '', $value['folder_name']) !!}
                                            @break
                                        @case('url')
                                            @php
                                                $url_string = $value['url'];
                                                foreach ($value['slash'] as $slash_string) {
                                                    $url_string .= '/' . $element->{$slash_string};
                                                }
                                            @endphp
                                            <a href="{{ $url_string }}" target="_blank">{{ $url_string }}</a>
                                            @break
                                        @case('serialNumber')
                                            {{ $list_key + 1 + (request('page', 1) - 1) * config('backend.paginate') }}
                                            @break
                                        @case('function')
                                            @php
                                                $function_name = explode('@', $value['function_name']);
                                                $class_name = $function_name[0];
                                            @endphp
                                            {{ $class_name::{$function_name[1]}($element) }}
                                            @break
                                        @default
                                            @if (isset($value['str_limit']))
                                                {{ $str->limit($element->{$value['column_name']}, $value['str_limit']) }}
                                            @else
                                                @if (is_array($value['column_name']))
                                                    @foreach ($value['column_name'] as $item)
                                                        <div>{{ $element->{$item} }}</div>
                                                    @endforeach
                                                @else
                                                    {{ $element->{$value['column_name']} }}
                                                @endif
                                            @endif
                                    @endswitch
                                </td>
                            @endforeach
                            @if (!$trashed)
                                @if (auth()->user()->hasAccess(['update-' . $permission_controller_string]))
                                    @if ($version)
                                        <td class="d-none d-md-table-cell">{{ $element->created_at }}</td>
                                    @endif
                                @endif
                                <td>
                                    @if (auth()->user()->hasAccess(['update-' . $permission_controller_string]) && !$version)
                                        @php
                                            $button_items['items']['檢視'] = ['url' => url($uri . 'detail?' . $id_string . '=' . $element->id)];
                                            if ($use_duplicate) {
                                                if (auth()->user()->hasAccess(['create-' . $permission_controller_string])) {
                                                    if (!isset($duplicate_url_suffix)) {
                                                        $duplicate_url_suffix = '';
                                                    }
                                                    $button_items['items']['複製'] = ['url' => url($uri . 'duplicate?' . $id_string . '=' . $element->id . $duplicate_url_suffix)];
                                                }
                                            }
                                            if (isset($preview_url) && !empty($preview_url)) {
                                                $button_items['items']['預覽'] = ['url' => url($preview_url['url'] . $element->{$preview_url['column']})];
                                            }
                                            if (isset($with)) {
                                                if (is_array($with)) {
                                                    $button_items['items']['關聯'] = [];
                                                    foreach ($with as $with_key => $with_value) {
                                                        $button_items['items']['關聯'][] = ['url' => url($with_value['url'] . $element->id), 'with_count' => $element->{$with_value['with_count_string']}, 'name' => $with_value['with_name'], 'icon' => $with_value['icon']];
                                                    }
                                                } else {
                                                    $button_items['items']['關聯'] = ['url' => url($preview_url . $element->id), 'with_count' => $element->$with_count_string, 'name' => $with_name, 'icon' => $icon];
                                                }
                                            }
                                            if (isset($custom_item)) {
                                                foreach ($custom_item as $custom_item_array) {
                                                    $custom_item_array['url'] .= $element->id;
                                                    $button_items['items']['自訂'][] = $custom_item_array;
                                                }
                                            }
                                            if ($use_version) {
                                                $button_items['items']['版本'] = ['url' => url($uri . 'index?' . $id_string . '=' . $element->id . '&version=true')];
                                            }
                                        @endphp
                                        @component('dashboard::components.backend-list-btn-group', $button_items)
                                            @if (isset($custom_button))
                                                @if (is_array($custom_button))
                                                    @foreach ($custom_button as $custom_button_item)
                                                        <a class="btn btn-outline-deep-purple waves-effect" href="{{ $custom_button_item['url'] . $element->id }}">
                                                            <i class="{{ $custom_button_item['icon'] }}"></i>
                                                            {{ $custom_button_item['with_name'] }}
                                                            <span class="badge badge-primary">
                                                                {{ $element->{$custom_button_item['with_count_string']} }}
                                                            </span>
                                                        </a>
                                                    @endforeach
                                                @else
                                                @endif
                                            @else
                                                <a href="{{ url($uri . 'update?' . $id_string . '=' . $element->id . ($update_url_append_string ?? '')) }}" class="btn btn-outline-deep-purple waves-effect text-nowrap">
                                                    <i class="fas fa-edit"></i>
                                                    <span class="d-none d-md-inline">@lang('backend.編輯')</span>
                                                </a>
                                            @endif
                                        @endcomponent
                                        
                                        {{-- @component('dashboard::components.dropdown-toggle', $button_items)
                                            @if (isset($custom_button))
                                                @if (is_array($custom_button))
                                                    @foreach ($custom_button as $custom_button_item)
                                                        <a class="btn btn-outline-deep-purple waves-effect" href="{{ $custom_button_item['url'] . $element->id }}">
                                                            <i class="{{ $custom_button_item['icon'] }}"></i>
                                                            {{ $custom_button_item['with_name'] }}
                                                            <span class="badge badge-primary">
                                                                {{ $element->{$custom_button_item['with_count_string']} }}
                                                            </span>
                                                        </a>
                                                    @endforeach
                                                @else
                                                @endif
                                            @else
                                                <a href="{{ url($uri . 'update?' . $id_string . '=' . $element->id . ($update_url_append_string ?? '')) }}" class="btn btn-outline-deep-purple waves-effect">
                                                    <i class="fas fa-edit"></i>
                                                    <span class="d-none d-md-inline">@lang('backend.編輯')</span>
                                                </a>
                                            @endif
                                        @endcomponent --}}
                                    @else
                                        @php
                                            $button_items = [];
                                            if ($version) {
                                                $button_items['items']['檢視'] = ['url' => url($uri . 'detail?' . $id_string . '=' . $element->id . '&origin_id=' . $element->origin_id . '&version=true')];
                                            }
                                        @endphp
                                        @component('dashboard::components.dropdown-toggle', $button_items)
                                            @if ($version)
                                                <a href="{{ url($uri . 'apply-version?' . $id_string . '=' . $element->origin_id . '&version_id=' . $element->id) }}" class="btn btn-outline-deep-purple waves-effect">
                                                    <i class="fas fa-code-branch"></i>@lang('backend.使用此版本')
                                                </a>
                                            @else
                                                <a href="{{ url($uri . 'detail?' . $id_string . '=' . $element->id) }}" class="btn btn-outline-deep-purple waves-effect">
                                                    <i class="fas fa-info"></i>@lang('backend.檢視')
                                                </a>
                                            @endif
                                        @endcomponent
                                    @endif
                                </td>
                                @if (($use_sort ?? true) && auth()->user()->hasAccess(['update-' . $permission_controller_string]))
                                    @if (!$version)
                                        @if ($use_rearrange ?? true)
                                            <td class="d-none d-md-table-cell sort_btn">
                                                <a href="{{ url($uri . 'rearrange?' . $id_string . '=' . $element->id . '&method=up&position=' . $list_key) }}"><i class="fas fa-caret-up"></i></a>
                                                <a href="{{ url($uri . 'rearrange?' . $id_string . '=' . $element->id . '&method=down&position=' . $list_key) }}"><i class="fas fa-caret-down"></i></a>
                                            </td>
                                        @endif
                                        <td class="d-none d-md-table-cell">
                                            <input type="text" name="sort[{{ $element->id }}]" class="form-control" value="{{ $element->sort }}" />
                                        </td>
                                    @endif
                                @endif
                            @endif
                        </tr>
                        {{-- 暫存排序資料 --}}
                        @php
                            $sort_array[] = [$element->id, $element->sort];
                        @endphp
                    @endforeach
                    @php
                        session()->flash('sort_array', $sort_array);
                    @endphp
                </tbody>
            </table>
        </div>
        <footer>
            <div class="row">
                <div class="col d-none d-md-inline">
                    @if (!isset($footer_dropdown_hide) || (isset($footer_dropdown_hide) && !$footer_dropdown_hide))
                        @if (!$version)
                            <div class="btn-group">
                                <div class="dropdown">
                                    <button class="btn btn-outline-deep-purple waves-effect dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        @lang('backend.選取項目')
                                    </button>
                                    <div class="dropdown-menu">
                                        @if ($trashed)
                                            @if (!isset($footer_status_hide) || (isset($footer_status_hide) && !$footer_status_hide))
                                                <button type="submit" name="force_delete" value="force_delete" class="dropdown-item">
                                                    <i class="fa fa-trash"></i>@lang('backend.永久刪除')
                                                </button>
                                                <button type="submit" name="restore" value="restore" class="dropdown-item">
                                                    <i class="fa fa-recycle"></i>@lang('backend.還原')
                                                </button>
                                            @endif
                                        @else
                                            @if (!isset($footer_status_hide) || (isset($footer_status_hide) && !$footer_status_hide))
                                                <button type="submit" name="status_enable" value="status_enable" class="dropdown-item">
                                                    <i class="fas fa-eye"></i>@lang('backend.啟用')
                                                </button>
                                                <button type="submit" name="status_disable" value="status_disable" class="dropdown-item">
                                                    <i class="fas fa-eye-slash"></i>@lang('backend.停用')
                                                </button>
                                            @endif
                                            @if (!isset($footer_delete_hide) || (isset($footer_delete_hide) && !$footer_delete_hide))
                                                <button type="submit" name="delete" value="delete" class="dropdown-item">
                                                    <i class="fa fa-trash"></i>@lang('backend.刪除')
                                                </button>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
                {{-- <div class="col-md-auto pt-2">
                    {{ $slot }}
                </div> --}}
                <div class="col-md-auto pt-2">
                    {!! $list->appends($qs)->links() !!}
                </div>
                <div class="col d-none d-md-inline">
                    @if (!isset($footer_sort_hide) || ($use_sort && isset($footer_sort_hide) && !$footer_sort_hide))
                        @if (!$trashed && !$version)
                            <div class="btn-group float-right">
                                <button type="submit" name="set_sort" value="set_sort" class="btn btn-outline-deep-purple waves-effect">
                                    <i class="fa fa-sort"></i>@lang('backend.修改排序')
                                </button>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </footer>
    </form>
    @endif
</div