@if (!isset($formPresenter))
    @inject('formPresenter', 'Onepoint\Dashboard\Presenters\FormPresenter')
@endif

@component('dashboard::' . config('backend.template') . '.components.inputs', $input_array = $formPresenter->setValue($input_setting, 'textarea'))
    <textarea class="form-control dashboard_textarea tinymce{{ $input_array['input_size_class'] }}" id="{{ $input_array['input_name'] }}" name="{{ $input_array['input_name'] }}" {!! $input_array['rows'] !!} {!! $input_array['attribute'] !!}>{{ $input_array['input_value'] }}</textarea>
@endcomponent