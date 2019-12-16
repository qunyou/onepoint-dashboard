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
                <a href="{{ url(config('dashboard.uri') . '/user/profile') }}">
                    @lang('auth.管理者')
                </a>
            </li>
        </ol>
    </nav>
@endsection

@section('main_block')
    @component('dashboard::components.backend-update-card', $component_datas)
        @include('dashboard::backend-update-input', ['form_array' => $form_array, 'form_value' => $user ?? ''])
    @endcomponent
@endsection
