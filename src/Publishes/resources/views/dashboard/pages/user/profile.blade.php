@extends(config('backend.view_path') . '.layouts.' . config('backend.layout_file'))

@section('title', config('site.name'))

@section('page-header', $page_header)

@section('js')
    @include('shared.laravel-filemanager')
    {{-- @include('shared.tinymcejs-rf') --}}
@endsection

@section('main_block')
    @component('shared.components.backend-update-card', $component_datas)
        @include('shared.backend-update-input', ['form_array' => $form_array, 'form_value' => $user ?? ''])
    @endcomponent
@endsection
