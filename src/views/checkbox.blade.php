@inject('formPresenter','App\Presenters\FormPresenter')
@component('shared.components.inputs', $input_array = $formPresenter->setValue($input_setting, 'radio'))
    @foreach ($input_array['option'] as $key => $element)
        <div class="form-check form-check-inline">
            <input type="checkbox" class="form-check-input" id="{{ $input_array['input_name'] . $key }}"  name="{{ $input_array['input_name'] }}" value="{{ $key }}" {{ in_array($key, $input_array['input_value']) ? ' checked' : '' }} {!! $input_array['attribute'] !!}>
            <label class="form-check-label" for="{{ $input_array['input_name'] . $key }}">{{ $element }}</label>
        </div>
    @endforeach
@endcomponent