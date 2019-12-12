@extends(config('backend.view_path') . '.layouts.' . config('backend.layout_file'))

@section('title', config('site.name'))

@section('page-header', $page_header)

@section('main_block')
    @component('shared.components.backend-detail-card', $component_datas)
        @include('shared.backend-update-input', ['form_array' => $form_array, 'form_value' => $setting ?? ''])
    @endcomponent
@endsection
