@extends(config('backend.view_path') . '.layouts.' . config('backend.layout_file'))

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
                <a href="{{ url(config('dashboard.uri') . '/layout/index') }}">
                    @lang('layout.排版')
                </a>
            </li>

            <li class="breadcrumb-item" aria-current="page">
                <a href="#">
                    @if ($layout_id)
                        @if ($duplicate)
                            @lang('backend.複製')
                        @else
                            @lang('backend.編輯')
                        @endif
                    @else
                        @lang('backend.新增')
                    @endif
                </a>
            </li>

            @if ($version)
                <li class="breadcrumb-item" aria-current="page">
                    <a href="#">
                        @lang('backend.版本檢視')
                    </a>
                </li>
            @endif
        </ol>
    </nav>
@endsection

@section('main_block')
    @component('shared.components.backend-update-card', $component_datas)
        @include('shared.backend-update-input', ['form_array' => $form_array, 'form_value' => $layout ?? ''])
    @endcomponent
@endsection
