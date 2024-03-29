@if (!isset($formPresenter))
    @inject('formPresenter', 'Onepoint\Dashboard\Presenters\FormPresenter')
@endif

@component('dashboard::' . config('backend.template') . '.components.inputs-group', $input_array = $formPresenter->setValue($input_setting))
    <input type="date" min="1911-01-01" max="9999-12-31" class="form-control {{ $input_array['input_size_class'] }}  @error($input_array['input_name']) is-invalid @enderror" id="{{ $input_array['input_name'] }}" name="{{ $input_array['input_name'] }}" value="{{ $input_array['input_value'] }}" {!! $input_array['attribute'] !!}>
    @error($input_array['input_name'])
        @slot('error_slot')
            <div class="alert alert-danger mt-1 form-text">{{ $message }}</div>
        @endslot
    @enderror
@endcomponent