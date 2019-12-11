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
            <li class="breadcrumb-item" aria-current="page">
                <a href="{{ url(config('dashboard.uri') . '/user/index') }}">
                    @lang('auth.人員管理')
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                <a href="{{ url($uri . 'detail?user_id=' . $user_id) }}">
                    @lang('auth.檢視人員')
                    @if ($version)
                         - @lang('backend.版本檢視')
                    @endif
                </a>
            </li>
        </ol>
    </nav>
@endsection

@section('main_block')
    @component('dashboard::components.backend-detail-card', $component_datas)
        @include('dashboard::backend-update-input', ['form_array' => $form_array, 'form_value' => $user ?? ''])
    @endcomponent
@endsection
