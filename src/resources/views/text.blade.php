@if (!isset($formPresenter))
    @inject('formPresenter', 'Onepoint\Dashboard\Presenters\FormPresenter')
@endif

@component('dashboard::' . config('backend.template') . '.components.inputs-group', $input_array = $formPresenter->setValue($input_setting, 'text'))
    <input type="text" class="form-control {{ $input_array['input_size_class'] }} @error($input_array['input_name']) is-invalid @enderror" id="{{ $input_array['input_name'] }} is-invalid" name="{{ $input_array['input_name'] }}" value="{{ $input_array['input_value'] }}" {!! $input_array['attribute'] !!} @if($input_array['required']) required @endif>
    @if($input_array['required'])
        <span class="input-group-text">@lang('dashboard::backend.必填')</span>
    @endif
    @error($input_array['input_name'])
        <div class="alert alert-danger mt-1">{{ $message }}</div>
    @enderror
@endcomponent