@extends('dashboard::' . config('backend.template') . '.layouts.dashboard')

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
                    @lang('dashboard::auth.人員管理')
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                @if ($user_id)
                    @if (isset($duplicate) && $duplicate)
                        <a href="{{ url($uri . 'duplicate?user_id=' . $user_id) }}">
                            @lang('dashboard::backend.複製')
                        </a>
                    @else
                        <a href="{{ url($uri . 'update?user_id=' . $user_id) }}">
                            @lang('dashboard::auth.編輯人員')
                        </a>
                    @endif
                @else
                    <a href="{{ url($uri . 'update') }}">
                        @lang('dashboard::auth.新增人員')
                    </a>
                @endif
            </li>
        </ol>
    </nav>
@endsection

@section('js')
    @include('dashboard::laravel-filemanager')
    {{-- @include('shared.tinymcejs-rf') --}}
@endsection

@section('main_block')
    @component('dashboard::' . config('backend.template') . '.components.backend-update-card', $component_datas)
        <div class="form-body">
            @include('dashboard::backend-update-input', ['form_array' => $form_array, 'form_value' => $user ?? ''])

            <footer>
                <button id="form-button" type="submit" class="btn btn-outline-deep-purple waves-effect">
                    @lang('dashboard::backend.送出')
                </button>
                <a href="{{ url($uri . 'index?' . $base_service->getQueryString(true, true, ['user_id'])) }}" class="btn btn-outline-deep-purple waves-effect">回上頁</a>
            </footer>
        </div>
    @endcomponent
@endsection
