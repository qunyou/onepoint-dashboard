@component('dashboard::components.inputs', $input_array = $formPresenter->setValue($input_setting, 'text'))
    <input type="text" class="form-control {{ $input_array['input_size_class'] }} @error($input_array['input_name']) is-invalid @enderror" id="{{ $input_array['input_name'] }}" name="{{ $input_array['input_name'] }}" value="{{ $input_array['input_value'] }}" {!! $input_array['attribute'] !!}>

    @error($input_array['input_name'])
        <div class="alert alert-danger mt-1">{{ $message }}</div>
    @enderror
@endcomponent