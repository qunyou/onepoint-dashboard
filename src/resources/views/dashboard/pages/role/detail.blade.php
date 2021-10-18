@extends('dashboard::' . config('backend.template') . '.layouts.dashboard')

@section('page-header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item" aria-current="page">
                <a href="{{ url(config('dashboard.uri') . '/dashboard/index') }}">
                    Home
                </a>
            </li>
            <li class="breadcrumb-item" aria-current="page">
                <a href="{{ url($uri . 'index') }}">
                    @lang('auth.人員群組')
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                <a href="{{ url($uri . 'detail?role_id=' . $role_id) }}">
                    @lang('auth.檢視人員群組')
                    @if ($version)
                        - @lang('backend.版本檢視')
                    @endif
                </a>
            </li>
        </ol>
    </nav>
@endsection

@section('main_block')
    @component('dashboard::' . config('backend.template') . '.components.backend-detail-card', $component_datas)
        <div class="row">
            <div class="col-md-12 col-lg-5">
                @include('dashboard::backend-update-input', ['form_array' => $form_array, 'form_value' => $role ?? ''])
            </div>
            <div class="col-md-12 col-lg-7">
                <ul class="list-group">
                    @foreach ($permissions as $permissions_key => $permissions_arr)
                        @if (auth()->user()->hasAccess(['read-' . $permissions_arr['controller']]))
                            <li class="list-group-item">
                                <div class="lead mb-2">{{ $permissions_key }}</div>
                                @foreach ($permissions_arr['permission'] as $permission_key => $permission)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input checkbox" type="checkbox" id="{{ $permissions_arr['controller'].$permission_key }}" value="1" name="{{ $permissions_arr['controller'] }}[{{ $permission_key }}]" {{ $role_presenter->is_check($role_permissions_array, $permission_key . '-' . $permissions_arr['controller']) }} disabled>
                                        <label class="form-check-label" for="{{ $permissions_arr['controller'].$permission_key }}">{{ $permission }}</label>
                                    </div>
                                @endforeach
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>
    @endcomponent
@endsection
