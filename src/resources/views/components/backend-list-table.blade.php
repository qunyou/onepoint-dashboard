@inject('image_service', 'Onepoint\Dashboard\Services\ImageService')

<div class="card-body">
    <table class="table table-hover">
        <thead>
            <tr>
                @if (auth()->user()->hasAccess(['update-' . $permission_controller_string]))
                    @if (!$version)
                        <th class="check_all_width d-none d-md-table-cell">
                            <input type="checkbox" name="select_all" id="select_all" value="" data-toggle="tooltip" data-original-title="@lang('backend.全選')" />
                        </th>
                    @endif
                @endif
                {!! $th !!}
                @if (!$trashed)
                    @if (auth()->user()->hasAccess(['update-' . $permission_controller_string]))
                        @if ($version)
                            <th class="d-none d-md-table-cell">@lang('backend.版本時間')</th>
                        @endif
                    @endif
                    <th scope="col"></th>
                    @if (auth()->user()->hasAccess(['update-' . $permission_controller_string]))
                        @if (!$version)
                            <th scope="col" class="th_sort_width d-none d-md-table-cell">@lang('backend.排序')</th>
                        @endif
                    @endif
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($list as $element)
                <tr {!! $element->status == '停用' ? ' class="table-dark"' : '' !!}>
                    @if (auth()->user()->hasAccess(['update-' . $permission_controller_string]))
                        @if (!$version)
                            <td class="d-none d-md-table-cell">
                                <input type="checkbox" name="checked_id[]" class="checkbox" value="{{ $element->id }}" />
                            </td>
                        @endif
                    @endif
                    @foreach ($column as $key => $value)
                        @if (is_array($value))
                            <td {!! $key == 0 ? '' : 'class="d-none d-md-table-cell"' !!}>
                                @switch($value[0])
                                    @case('belongsToMany')
                                        {{ $element->{$value[1]}->implode($value[2], ',') }}
                                        @break
                                    @case('belongsTo')
                                        {{ $element->{$value[1]}->{$value[2]} }}
                                        @break
                                    @case('badges')
                                        {{ $element->{$value[1]} }}<br>
                                        @foreach ($value[2] as $badge_key => $badge_value)
                                            <span class="{{ $badge_value['class'] }}">{{ $badge_value['badge_title'] }}{{ $element->{$badge_key} }}</span>
                                        @endforeach
                                        @break
                                    @case('image')
                                        {!! $image_service->{$value[1]}($element->{$value[2]}, '', '', $value[3]) !!}
                                        @break
                                @endswitch
                            </td>
                        @else
                            <td {!! $key == 0 ? '' : 'class="d-none d-md-table-cell"' !!}>
                                @if ($value == 'status')
                                    {{ __('backend.' . $element->$value) }}
                                @else
                                    {{ $element->$value }}
                                @endif
                            </td>
                        @endif
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
                                            $button_items['items']['複製'] = ['url' => url($uri . 'duplicate?' . $id_string . '=' . $element->id)];
                                        }
                                    }
                                    if (isset($preview_url) && !empty($preview_url)) {
                                        $button_items['items']['預覽'] = ['url' => url($preview_url . $element->id)];
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
                                    if ($use_version) {
                                        $button_items['items']['版本'] = ['url' => url($uri . 'index?' . $id_string . '=' . $element->id . '&version=true')];
                                    }
                                @endphp
                                @component('dashboard::components.dropdown-toggle', $button_items)
                                    @if (isset($custom_button))
                                        @if (is_array($custom_button))
                                            @foreach ($custom_button as $custom_button_item)
                                                <a class="btn btn-outline-deep-purple waves-effect" href="{{ $custom_button_item['url'] . $element->id }}">
                                                    <i class="{{ $custom_button_item['icon'] }}"></i>{{ $custom_button_item['with_name'] }}<span class="badge badge-primary">{{ $element->{$custom_button_item['with_count_string']} }}</span>
                                                </a>
                                            @endforeach
                                        @else
                                        @endif
                                    @else
                                        <a href="{{ url($uri . 'update?' . $id_string . '=' . $element->id . ($update_url_append_string ?? '')) }}" class="btn btn-outline-deep-purple waves-effect">
                                            <i class="fas fa-edit"></i>@lang('backend.編輯')
                                        </a>
                                    @endif
                                @endcomponent
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
                        @if (auth()->user()->hasAccess(['update-' . $permission_controller_string]))
                            @if (!$version)
                                <td class="d-none d-md-table-cell">
                                    <input type="text" name="sort[{{ $element->id }}]" class="form-control" value="{{ $element->sort }}" />
                                </td>
                            @endif
                        @endif
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@component('dashboard::components.backend-list-footer', ['trashed' => $trashed, 'version' => $version, 'footer_dropdown_hide' => $footer_dropdown_hide, 'footer_sort_hide' => $footer_sort_hide, 'footer_delete_hide' => $footer_delete_hide])
    {!! $list->appends($qs)->links() !!}
    <div class="text-center">
        @lang('backend.共') {{ $list->total() }} @lang('backend.筆資料')
    </div>
@endcomponent