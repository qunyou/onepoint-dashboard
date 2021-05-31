@if (!isset($formPresenter))
    @inject('formPresenter', 'Onepoint\Dashboard\Presenters\FormPresenter')
@endif

@component('dashboard::components.inputs', $input_array = $formPresenter->setValue($input_setting, 'number'))
    @php
        $input_value = 0;
        if (isset($input_array['value_type']) && $input_array['value_type'] == 'integer') {
            if ($input_array['input_value'] > 0) {
                // $input_value = number_format($input_array['input_value'], $input_array['parameter']['decimals'] ?? 0);
                $input_value = $input_array['input_value'] + 0;
            }
        } else {
            if ($input_array['input_value'] > 0) {
                $input_value = $input_array['input_value'] ?? 0;
            }
        }
    @endphp
    <input type="number" class="form-control{{ $input_array['input_size_class'] }}" id="{{ $input_array['input_name'] }}" name="{{ $input_array['input_name'] }}" value="{{ $input_value }}" {!! $input_array['attribute'] !!}{{-- step="any" --}}>
@endcomponent