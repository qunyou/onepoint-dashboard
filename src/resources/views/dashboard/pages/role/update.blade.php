@inject('role_presenter', 'Onepoint\Dashboard\Presenters\RolePresenter')

@extends('dashboard::' . config('backend.template') . '.layouts.dashboard')

@section('title', config('site.name'))

@section('page-header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item" aria-current="page">
                <a href="{{ url(config('dashboard.uri') . '/dashboard/index') }}">
                    <i class="fas fa-home"></i>
                </a>
            </li>
            <li class="breadcrumb-item" aria-current="page">
                <a href="{{ url($uri . 'index') }}">
                    @lang('dashboard::auth.人員群組')
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                @if ($role_id)
                    <a href="{{ url($uri . 'update?role_id=' . $role_id) }}">
                        @lang('dashboard::auth.編輯人員群組')
                    </a>
                @else
                    <a href="{{ url($uri . 'update') }}">
                        @lang('dashboard::auth.新增人員群組')
                    </a>
                @endif
            </li>
        </ol>
    </nav>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $('#select_all').on('click',function() {
                if(this.checked){
                    $('.checkbox').each(function() {
                        this.checked = true;
                    });
                }else{
                    $('.checkbox').each(function() {
                        this.checked = false;
                    });
                }
            });

            $('.checkbox').on('click',function() {
                if($('.checkbox:checked').length == $('.checkbox').length) {
                    $('#select_all').prop('checked',true);
                }else{
                    $('#select_all').prop('checked',false);
                }
            });
        });
    </script>
@endsection

@section('main_block')
    @component('dashboard::' . config('backend.template') . '.components.backend-update-card')
        @slot('button_block')
            {{-- <a class="btn btn-primary" href="{{ url($uri . 'index?' . $base_service->getQueryString(true, true, ['role_id'])) }}">
                <i class="fa fa-fw fa-arrow-left"></i>@lang('dashboard::auth.人員群組列表')
            </a> --}}
        @endslot
        @include('dashboard::text', ['input_setting' => [
            'display_name' => __('dashboard::auth.群組名稱'),
            'input_name' => 'role_name',
            'input_value' => old('role_name', optional($role)->role_name),
        ]])
        
        <div class="alert alert-primary pl-5">
            <input type="checkbox" name="select_all" class="form-check-input" id="select_all" value="">
            <label class="form-check-label" for="select_all">{{ trans('dashboard::backend.全選') }}</label>
        </div>
        <ul class="list-group mb-2">
            @foreach ($permissions as $permissions_key => $permissions_arr)
                @if (auth()->user()->hasAccess(['read-' . $permissions_arr['controller']]))
                    <li class="list-group-item mb-2">
                        <div class="lead mb-2">{{ $permissions_key }}</div>
                        @foreach ($permissions_arr['permission'] as $permission_key => $permission)
                            @if ($permission == 'update')
                                <input type="hidden" value="update" name="{{ $permissions_arr['controller'] }}[{{ $permission_key }}]">
                                {{-- $role_permissions_array[$permission_key . '-' . $permissions_arr['controller']] --}}
                            @else
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input checkbox" type="checkbox" id="{{ $permissions_arr['controller'].$permission_key }}" value="1" name="{{ $permissions_arr['controller'] }}[{{ $permission_key }}]" {{ $role_presenter->is_check($role_permissions_array, $permission_key . '-' . $permissions_arr['controller']) }}>
                                    <label class="form-check-label" for="{{ $permissions_arr['controller'].$permission_key }}">{{ $permission }}</label>
                                </div>
                            @endif
                        @endforeach
                    </li>
                @endif
            @endforeach
        </ul>
        <button id="form-button" type="submit" class="btn btn-primary">
            @lang('dashboard::backend.送出')
        </button>
    @endcomponent
@endsection