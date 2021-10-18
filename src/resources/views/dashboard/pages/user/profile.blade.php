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
                <a href="{{ url(config('dashboard.uri') . '/user/profile') }}">
                    @lang('auth.管理者')
                </a>
            </li>
        </ol>
    </nav>
@endsection

@section('main_block')
    @component('dashboard::' . config('backend.template') . '.components.backend-update-card', $component_datas)
        <div class="form-body">    
            @include('dashboard::backend-update-input', ['form_array' => $form_array, 'form_value' => $user ?? ''])
        </div>
    @endcomponent
@endsection
