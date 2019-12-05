@inject('formPresenter','App\Presenters\FormPresenter')
@component('shared.components.inputs', $input_array = $formPresenter->setValue($input_setting, 'number'))
    <input type="number" class="form-control{{ $input_array['input_size_class'] }}" id="{{ $input_array['input_name'] }}" name="{{ $input_array['input_name'] }}" value="{{ $input_array['input_value'] }}" {!! $input_array['attribute'] !!}>
@endcomponent