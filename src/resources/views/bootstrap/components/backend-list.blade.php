@inject('image_service', 'Onepoint\Dashboard\Services\ImageService')
@inject('base_service', 'Onepoint\Dashboard\Services\BaseService')
{{-- @inject('str', 'Illuminate\Support\Str') --}}

@if (isset($page_title))
<div class="container-fluid">
    <div class="h4 py-3">
        {!! $page_title !!}
        @if ($version)
            - @lang('dashboard::backend.版本檢視')
        @endif
    </div>
</div>
@endif

@if (isset($button_block))
<div class="px-2 py-3 overflow-x">
    {!! $button_block !!}
</div>
@endif

{!! $search_block ?? '' !!}

@if (isset($list) && $list)
    <div class="mt-2 text-center p-2">
        @lang('dashboard::pagination.共') {{ $list->lastPage() }} @lang('dashboard::pagination.頁')，
        {{ $list->total() }} @lang('dashboard::pagination.筆資料')，
        @lang('dashboard::pagination.目前在第') {{ $list->currentPage() }} @lang('dashboard::pagination.頁')，
        @lang('dashboard::pagination.每頁顯示')
        <span class="dropdown">
            <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                {{ cache('records_per_page', '') }} @lang('dashboard::pagination.筆資料')
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                <li><a href="{{ url($uri . 'index?records_per_page=50') }}" class="dropdown-item {{ cache('records_per_page', false) == 50 ? 'active' : '' }}">@lang('dashboard::pagination.每頁顯示') 50 @lang('dashboard::pagination.筆資料')</a></li>
                <li><a href="{{ url($uri . 'index?records_per_page=100') }}" class="dropdown-item {{ cache('records_per_page', false) == 100 ? 'active' : '' }}">@lang('dashboard::pagination.每頁顯示') 100 @lang('dashboard::pagination.筆資料')</a></li>
                <li><a href="{{ url($uri . 'index?records_per_page=1000') }}" class="dropdown-item {{ cache('records_per_page', false) == 1000 ? 'active' : '' }}">@lang('dashboard::pagination.每頁顯示') 1000 @lang('dashboard::pagination.筆資料')</a></li>
            </ul>
        </span>
    </div>
    <div class="overflow-x">
        {!! $list->appends($base_service->getQueryString())->links('dashboard::vendor.pagination.default') !!}
    </div>
    <form action="" method="post">
        @csrf
        @method('PUT')
        <div class="table-responsive card-list">
            <table id="row-table" class="table table-hover">
                <thead>
                    <tr class="d-none d-md-table-row table-primary">
                        @if (!config('user.use_role') || auth()->user()->hasAccess(['update-' . $permission_controller_string]))
                            @if (!$version)
                                @if (isset($use_drag_rearrange) && $use_drag_rearrange)
                                    <th class="d-none d-md-table-cell drag_width">@lang('dashboard::backend.排序')</th>
                                @endif
                                @if (isset($use_check_box) && $use_check_box)
                                <th class="check_all_width">
                                    <input type="checkbox" name="select_all" id="select_all" value="" data-toggle="tooltip" data-original-title="@lang('dashboard::backend.全選')" />
                                </th>
                                @endif
                            @endif
                        @endif
                        @foreach ($th as $th_key => $element)
                            <th class="text-nowrap">{{ $element['title'] }}</th>
                        @endforeach
                        @if (!$trashed)
                            @if (!config('user.use_role') || auth()->user()->hasAccess(['update-' . $permission_controller_string]))
                                @if ($version)
                                    <th>@lang('dashboard::backend.版本時間')</th>
                                @endif
                            @endif
                            <th scope="col"></th>
                            @if (($use_sort ?? true) && (!config('user.use_role') ||  auth()->user()->hasAccess(['update-' . $permission_controller_string])))
                                @if (!$version)
                                    @if ($use_rearrange ?? true)
                                        <th></th>
                                    @endif
                                    <th>@lang('dashboard::backend.排序')</th>
                                @endif
                            @endif
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($list as $list_key => $element)
                        @php
                            $css_class_name = '';
                            if (isset($td_color)) {
                                if ($td_color) {
                                    if (is_array($td_color)) {
                                        foreach ($td_color as $condition) {
                                            if ($element->{$condition['column']} == $condition['value']) {
                                                $css_class_name = $condition['class'];
                                            } else {
                                                $css_class_name = $condition['else'];
                                            }
                                        }
                                    } else {
                                        $function_name = explode('@', $td_color);
                                        $class_name = $function_name[0];
                                        $css_class_name = $class_name::{$function_name[1]}($element);
                                    }
                                }
                            } else {
                                $css_class_name = $element->{config('db_status_name')} == config('db_status_false_string') ? 'table-dark' : 'table-light';
                            }
                        @endphp
                        <tr id="{{ $element->id }}" class="{{ $css_class_name }}">
                            @if (!config('user.use_role') || auth()->user()->hasAccess(['update-' . $permission_controller_string]))
                                @if (!$version)
                                    @if (isset($use_drag_rearrange) && $use_drag_rearrange)
                                        <td class="drag d-none d-md-table-cell text-center">
                                            <i class="fas fa-arrows-alt-v move"></i>
                                        </td>
                                    @endif
                                    @if (isset($use_check_box) && $use_check_box)
                                    <td>
                                        <input type="checkbox" name="checked_id[]" class="checkbox" value="{{ $element->id }}" />
                                    </td>
                                    @endif
                                @endif
                                @foreach ($column as $column_key => $value)
                                    <td>
                                        <span class="d-md-none">{{ $th[$column_key]['title'] }}：</span>
                                        @switch($value['type'])
                                            @case('belongsToMany')
                                                @if (is_array($value['column_name']))
                                                    @foreach ($element->{$value['with']} as $with_item)
                                                        <div>
                                                            @foreach ($value['column_name'] as $item)
                                                                {{ $with_item->{$item} }}{!! $value['delimiter_string'] !!}
                                                            @endforeach
                                                        </div>
                                                    @endforeach
                                                @else
                                                    @if (isset($value['url']))
                                                        @foreach ($element->{$value['with']} as $item)
                                                            <a href="{{ $value['url'] . $item->id }}">{{ $item->{$value['column_name']} }}</a>
                                                        @endforeach
                                                    @else
                                                        {!! $element->{$value['with']}->implode($value['column_name'], $value['delimiter_string'] ?? ',') !!}
                                                    @endif
                                                @endif
                                                @break
                                            @case('belongsToManyImage')
                                                @if ($element->{$value['with']}->count())
                                                        @if (isset($value['img_url']))
                                                            <img src="{{ $value['img_url'] . $element->{$value['with']}->first()->{$value['column_name']} }}" alt="">
                                                        @else
                                                            {!! $image_service->{$value['method']}($element->{$value['with']}->first()->{$value['column_name']}, '', false, $value['folder_name']) !!}
                                                        @endif
                                                @endif
                                                @break
                                            @case('belongsTo')
                                                @if (is_array($value['column_name']))
                                                    @foreach ($value['column_name'] as $column_name_key => $column_name_item)
                                                        @php
                                                            $column_name_array = explode('->', $column_name_item);
                                                            $column_name_array_count = count($column_name_array);
                                                            if ($column_name_array_count > 1) {
                                                                if (is_array($value['with'])) {
                                                                    $value_string = $element[$value['with'][$column_name_key]];
                                                                } else {
                                                                    $value_string = $element[$value['with']];
                                                                }
                                                                for ($i=0; $i < $column_name_array_count; $i++) { 
                                                                    $value_string = $value_string[$column_name_array[$i]];
                                                                }
                                                            } else {
                                                                if (is_array($value['with'])) {
                                                                    $value_string = $element->{$value['with'][$column_name_key]}->{$column_name_item} ?? '';
                                                                } else {
                                                                    $value_string = $element->{$value['with']}->{$column_name_item} ?? '';
                                                                }
                                                            }
                                                        @endphp
                                                        {{ $value_string }}{!! $value['delimiter_string'] !!}
                                                    @endforeach
                                                @else
                                                    @php
                                                        $column_name_array = explode('->', $value['column_name']);
                                                        $column_name_array_count = count($column_name_array);
                                                        if ($column_name_array_count > 1) {
                                                            $value_string = $element[$value['with']];
                                                            for ($i=0; $i < $column_name_array_count; $i++) { 
                                                                $value_string = $value_string[$column_name_array[$i]];
                                                            }
                                                        } else {
                                                            $value_string = $element->{$value['with']}->{$value['column_name']} ?? '';
                                                        }
                                                    @endphp
                                                    {{ $value_string ?? '' }}
                                                @endif
                                                @break
                                            @case('belongsToSelect')
                                                <select name="{{ $value['column_name'] }}" id="{{ $value['column_name'] }}" class="form-control" v-model="{{ $value['vmodel'] . $list_key }}" {!! isset($value['onchange_function']) ? '@change="' . $value['onchange_function'] . '($event, ' . $element->id . ')"' : '' !!}>
                                                    @if (isset($value['option_vshow']))
                                                        <option value="0">請選擇</option>
                                                    @endif
                                                    @foreach ($value['option_items'] as $option_key => $option_item)
                                                        @if (isset($value['option_vshow']))
                                                            <option value="{{ $option_key }}" {!! 'v-show="' . $option_item[$value['parent_id_string']] . ' == ' . $value['option_vshow'] . $list_key . '"' !!}>{{ $option_item[$value['item_key']] }}</option>
                                                        @else
                                                            <option value="{{ $option_key }}">{{ $option_item }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                @break
                                            @case('belongsToSum')
                                                {{ $element->{$value['with']}->sum($value['column_name']) ?? '' }}
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
                                                <a href="{{ $url_string }}" class="text-dark" target="_blank">{{ $url_string }}</a>
                                                @break
                                            @case('boolean')
                                                @if (isset($value['option']))
                                                    {{ $element->{$value['column_name']} == 1 ? $value['option'][0] : $value['option'][1] }}
                                                @else
                                                    {{ $element->{$value['column_name']} == 1 ? '是' : '否' }}
                                                @endif
                                                @break
                                            @case('serialNumber')
                                                {{ $list_key + 1 + (request('page', 1) - 1) * config('backend.paginate') }}
                                                @break
                                            @case('function')
                                                @php
                                                    $function_name = explode('@', $value['function_name']);
                                                    $class_name = $function_name[0];
                                                @endphp
                                                {!! $class_name::{$function_name[1]}($element) !!}
                                                @break
                                            @case('date')
                                                @if (isset($value['format']))
                                                    @if (!is_null($element->{$value['column_name']}))
                                                        {{ date($value['format'], strtotime($element->{$value['column_name']}))}}
                                                    @endif
                                                @else
                                                    {{ $element->{$value['column_name']} }}
                                                @endif
                                                @break
                                            @default
                                                @if (isset($value['str_limit']))
                                                    {{ $str->limit($element->{$value['column_name']}, $value['str_limit']) }}
                                                @else
                                                    @if (is_array($value['column_name']))
                                                        @foreach ($value['column_name'] as $key => $item)
                                                            <div>
                                                                @if (is_string($key))
                                                                    <span class="badge badge-primary">{{ $key }}：
                                                                @endif
                                                                {{ $element->{$item} }}
                                                                @if (is_string($key))
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        {{ $element->{$value['column_name']} }}
                                                    @endif
                                                @endif
                                        @endswitch
                                    </td>
                                @endforeach
                                @if (!$trashed)
                                    @if (!config('user.use_role') || auth()->user()->hasAccess(['update-' . $permission_controller_string]))
                                        @if ($version)
                                            <td>{{ $element->created_at }}</td>
                                        @endif
                                    @endif
                                    <td class="text-nowrap text-end">
                                        @if ($version)
                                            @php
                                                $button_items['status'] = $element->{config('db_status_name')};
                                                if ($version) {
                                                    $button_items['items']['檢視'] = ['url' => url($uri . 'detail?' . $id_string . '=' . $element->id . '&origin_id=' . $element->origin_id . '&version=true')];
                                                }
                                            @endphp
                                            {{-- @component('dashboard::' . config('backend.template') .  '.components.dropdown-toggle', $button_items) --}}
                                            @component('dashboard::' . config('backend.template') .  '.components.backend-list-btn-group', $button_items)
                                                @if ($version)
                                                    <a href="{{ url($uri . 'apply-version?' . $id_string . '=' . $element->origin_id . '&version_id=' . $element->id) }}" class="btn {{ $element->{config('db_status_name')} == config('db_status_false_string') ? 'btn-secondary' : 'btn-primary' }}">
                                                        <i class="fas fa-code-branch"></i>@lang('dashboard::backend.使用此版本')
                                                    </a>
                                                @else
                                                    <a href="{{ url($uri . 'detail?' . $id_string . '=' . $element->id) }}" class="btn {{ $element->{config('db_status_name')} == config('db_status_false_string') ? 'btn-secondary' : 'btn-primary' }}">
                                                        <i class="fas fa-info"></i>@lang('dashboard::backend.檢視')
                                                    </a>
                                                @endif
                                            @endcomponent
                                        @else
                                            @if (!config('user.use_role') || auth()->user()->hasAccess(['update-' . $permission_controller_string]))
                                                @php
                                                    $button_items['status'] = $element->{config('db_status_name')};
                                                    if (isset($detail_hide) && $detail_hide) {
                                                        // $button_items = [];
                                                    } else {
                                                        $button_items['items']['檢視'] = ['url' => url($uri . 'detail?' . $id_string . '=' . $element->id . '&' . $base_service->getQueryString(true, true))];
                                                    }
                                                    if ($use_duplicate) {
                                                        if (!config('user.use_role') || auth()->user()->hasAccess(['create-' . $permission_controller_string])) {
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
                                                                $with_url = '';
                                                                $qs = '';
                                                                if (isset($with_value['url'])) {
                                                                    if (isset($with_value['refer_id_string'])) {
                                                                        if (isset($with_value['refer_id_column'])) {
                                                                            $with_value['query_string_suffix'][$with_value['refer_id_string']] = $element->{$with_value['refer_id_column']};
                                                                        } else {
                                                                            $with_value['query_string_suffix'][$with_value['refer_id_string']] = $element->id;
                                                                        }
                                                                    } else {
                                                                        $with_value['query_string_suffix'][$id_string] = $element->id;
                                                                    }
                                                                    if (isset($with_value['query_string_append']) && is_array($with_value['query_string_append'])) {
                                                                        foreach ($with_value['query_string_append'] as $query_string_append_key => $query_string_append_value) {
                                                                            $with_value['query_string_suffix'][$query_string_append_key] = $query_string_append_value;
                                                                        }
                                                                    }
                                                                    $page = request('page', 0);
                                                                    if (isset($with_value['back_page_string']) && $page > 0) {
                                                                        $with_value['query_string_suffix'][$with_value['back_page_string']] = $page;
                                                                    }
                                                                    $qs = '&' . http_build_query($with_value['query_string_suffix']);
                                                                    $with_url = url($with_value['url'] . '?' . $base_service->getQueryString(true, true, [$id_string, 'page']) . $qs);
                                                                }
                                                                $button_items['items']['關聯'][] = ['url' => $with_url, 'with_count' => $element->{$with_value['with_count_string']}, 'name' => $with_value['with_name'], 'icon' => $with_value['icon']];
                                                            }
                                                        } else {
                                                            $button_items['items']['關聯'] = ['url' => url($preview_url . $element->id), 'with_count' => $element->$with_count_string, 'name' => $with_name, 'icon' => $icon];
                                                        }
                                                    }
                                                    if (isset($custom_item)) {
                                                        $button_items['items']['自訂'] = [];
                                                        foreach ($custom_item as $custom_item_array) {
                                                            $custom_item_array['url'] .= $element->id;
                                                            $button_items['items']['自訂'][] = $custom_item_array;
                                                        }
                                                    }
                                                    if ($use_version) {
                                                        $button_items['items']['版本'] = ['url' => url($uri . 'index?' . $id_string . '=' . $element->id . '&version=true')];
                                                    }
                                                @endphp
                                                @component('dashboard::' . config('backend.template') .  '.components.backend-list-btn-group', $button_items)
                                                    @if (isset($custom_button))
                                                        @if (is_array($custom_button))
                                                            @foreach ($custom_button as $custom_button_item)
                                                                <a class="btn btn-primary" href="{{ $custom_button_item['url'] . $element->id }}">
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
                                                        @if (isset($update_hide) && $update_hide)
                                                        @else
                                                            <a href="{{ url($uri . ($update_uri ?? 'update') . '?' . $id_string . '=' . $element->id . '&' . $base_service->getQueryString(true, true)) }}" class="btn {{ $element->{config('db_status_name')} == config('db_status_false_string') ? 'btn-secondary' : 'btn-primary' }}">
                                                                <i class="fas fa-edit"></i>
                                                                @lang('dashboard::backend.編輯')
                                                            </a>
                                                        @endif
                                                    @endif
                                                @endcomponent
                                            @endif
                                        @endif
                                    </td>
                                    @if (($use_sort ?? true) && (!config('user.use_role') || auth()->user()->hasAccess(['update-' . $permission_controller_string])))
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
                            @endif
                        </tr>
                        {{-- 暫存排序資料 --}}
                        @php
                            $sort_array[] = [$element->id, $element->sort];
                        @endphp
                    @endforeach
                    @php
                        cache(['sort_array' => $sort_array], 6000);
                        // session()->flash('sort_array', $sort_array);
                    @endphp
                </tbody>
            </table>
        </div>
        <footer>
            <div class="row">
                <div class="col-auto">
                    @if (!isset($footer_dropdown_hide) || (isset($footer_dropdown_hide) && !$footer_dropdown_hide))
                        @if (!$version)
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownFooterMenu" data-bs-toggle="dropdown" aria-expanded="false">
                                    @lang('dashboard::backend.選取項目')
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownFooterMenu">
                                    @if ($trashed)
                                        @if (!isset($footer_status_hide) || (isset($footer_status_hide) && !$footer_status_hide))
                                        <li>
                                            <button type="submit" name="force_delete" value="force_delete" class="dropdown-item">
                                                <i class="fa fa-trash"></i>@lang('dashboard::backend.永久刪除')
                                            </button>
                                        </li>
                                        <li>
                                            <button type="submit" name="restore" value="restore" class="dropdown-item">
                                                <i class="fa fa-recycle"></i>@lang('dashboard::backend.還原')
                                            </button>
                                        </li>
                                        @endif
                                    @else
                                        @if (!isset($footer_status_hide) || (isset($footer_status_hide) && !$footer_status_hide))
                                            <li>
                                                <button type="submit" name="status_enable" value="status_enable" class="dropdown-item">
                                                    <i class="fas fa-eye"></i>@lang('dashboard::backend.啟用')
                                                </button>
                                            </li>
                                            <li>
                                                <button type="submit" name="status_disable" value="status_disable" class="dropdown-item">
                                                    <i class="fas fa-eye-slash"></i>@lang('dashboard::backend.停用')
                                                </button>
                                            </li>
                                        @endif
                                        @if (!isset($footer_delete_hide) || (isset($footer_delete_hide) && !$footer_delete_hide))
                                            <li>
                                                <button type="submit" name="delete" value="delete" class="dropdown-item">
                                                    <i class="fa fa-trash"></i>@lang('dashboard::backend.刪除')
                                                </button>
                                            </li>
                                        @endif
                                    @endif
                                    {!! $footer_dropdown_item ?? '' !!}
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
                <div class="col-auto">
                    @if (!isset($footer_sort_hide) || ($use_sort && isset($footer_sort_hide) && !$footer_sort_hide))
                        @if (!$trashed && !$version)
                            <div class="btn-group float-right">
                                <button type="submit" name="set_sort" value="set_sort" class="btn btn-primary">
                                    <i class="fa fa-sort"></i>@lang('dashboard::backend.修改排序')
                                </button>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </footer>
    </form>
@endif
{!! $custom_block ?? '' !!}