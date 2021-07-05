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
                <a href="{{ url(config('dashboard.uri') . '/member/index') }}">
                    @lang('base::member.會員')
                </a>
            </li>
            <li class="breadcrumb-item" aria-current="page">
                <a href="#">
                    @if ($member_id)
                        @if (isset($duplicate) && $duplicate)
                            @lang('dashboard::backend.複製')
                        @else
                            @lang('dashboard::backend.編輯')
                        @endif
                    @else
                        @lang('dashboard::backend.新增')
                    @endif
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
    @component('dashboard::components.backend-update-card', $component_datas)
        <div class="form-body">
            @include('dashboard::backend-update-input', ['form_array' => $form_array_normal, 'form_value' => $member ?? ''])
        </div>
    @endcomponent
@endsection
