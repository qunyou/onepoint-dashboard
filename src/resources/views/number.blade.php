@component('dashboard::components.inputs', $input_array = $formPresenter->setValue($input_setting, 'number'))
    @php
        if ($input_array['value_type'] == 'integer') {
            $input_value = number_format($input_array['input_value'], $input_array['parameter']['decimals'] ?? 0);
        } else {
            $input_value = $input_array['input_value'];
        }
    @endphp
    <input type="number" class="form-control{{ $input_array['input_size_class'] }}" id="{{ $input_array['input_name'] }}" name="{{ $input_array['input_name'] }}" value="{{ $input_value }}" {!! $input_array['attribute'] !!}{{-- step="any" --}}>
@endcomponent